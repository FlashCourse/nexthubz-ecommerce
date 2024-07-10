<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Jobs\CheckOrderStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BkashController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->base_url = config('services.bkash.base_url');
        $this->app_key = config('services.bkash.app_key');
        $this->app_secret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');

        $this->stockService = $stockService;
    }

    public function index()
    {
        $cartData = session('cartData', []);
        $orderData = session('orderData', []);

        // Check if both cartData and orderData are empty
        if (empty($cartData) && empty($orderData)) {
            abort(404);
        }

        return view('bkash-payment', compact('cartData', 'orderData'));
    }

    public function getToken()
    {
        Session::forget('bkash_token');

        $post_token = [
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        ];

        $url = "$this->base_url/checkout/token/grant";
        $header = [
            'Content-Type' => 'application/json',
            'password' => $this->password,
            'username' => $this->username
        ];

        $response = Http::withHeaders($header)->post($url, $post_token);
        $responseData = $response->json();

        if (array_key_exists('msg', $responseData)) {
            return $responseData;
        }

        Session::put('bkash_token', $responseData['id_token']);

        return response()->json(['success' => true]);
    }

    public function createPayment(Request $request)
    {
        $user = Auth::user();

        $cartItems = [];

        $orderData = [];

        $addressData = [];

        $addressData = session()->get('addressData');

        $orderData = session()->get('orderData');

        $cartItems = session()->get('cartData');

        // Start a database transaction
        DB::beginTransaction();

        try {

            // Retrieve the quantities for all products and variants
            $productQuantities = [];
            foreach ($cartItems as $item) {
                $productQuantities[] = [
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity']
                ];
            }

            $this->stockService->deductStock($productQuantities);

            // Use firstOrCreate directly on the Address model
            $address = Address::firstOrCreate(['user_id' => $user->id], $addressData);

            // create order
            $order = Order::create([
                'user_id' => $user->id,
                'address' => $address->id,
                'payment_method' => 'online',
                'tax' => $orderData['tax'],
                'shipping' => $orderData['shipping'],
                'subtotal' => $orderData['subtotal'],
                'total' => $orderData['total'],
                'status' => 'pending',
            ]);

            // Prepare data for order items insertion
            $orderItemsData = [];
            foreach ($cartItems as $item) {
                $orderItem = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ];

                if (!empty($item['variant_id'])) {
                    $orderItem['variant_id'] = $item['variant_id'];
                }

                $orderItemsData[] = $orderItem;
            }

            // Insert all the order items to the CartItem
            OrderItem::insert($orderItemsData);

            // Dispatch the job to rollback stock if checkout failed
            CheckOrderStatus::dispatch($order->id)->delay(now()->addMinutes(30));

            session()->forget('cartData');
            session()->forget('orderData');

            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            // Handle the exception, log or throw it if necessary
            throw $e;
        }

        $token = Session::get('bkash_token');

        // Dynamically merge the amount into the request
        $request->merge([
            'amount' => round($orderData['total'], 2),
            'intent' => 'sale',
            'currency' => 'BDT',
            'merchantInvoiceNumber' => rand()
        ]);

        $url = "$this->base_url/checkout/payment/create";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->post($url, $request->all());

        return $response->json();
    }

    public function executePayment(Request $request)
    {
        $token = Session::get('bkash_token');

        $paymentID = $request->paymentID;
        $url = "$this->base_url/checkout/payment/execute/$paymentID";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->post($url);

        return $response->json();
    }

    public function queryPayment(Request $request)
    {
        $token = Session::get('bkash_token');
        $paymentID = $request->payment_info['payment_id'];

        $url = "$this->base_url/checkout/payment/query/$paymentID";
        $header = [
            'Content-Type' => 'application/json',
            'authorization' => $token,
            'x-app-key' => $this->app_key
        ];

        $response = Http::withHeaders($header)->get($url);

        return $response->json();
    }

    public function successPayment(Request $request)
    {

        // IF PAYMENT SUCCESS THEN YOU CAN APPLY YOUR CONDITION HERE
        if ('Noman' == 'success') {

            // THEN YOU CAN REDIRECT TO YOUR ROUTE

            Session::flash('successMsg', 'Payment has been Completed Successfully');

            return response()->json(['status' => true]);
        }

        Session::flash('error', 'Noman Error Message');

        return response()->json(['status' => false]);
    }
}
