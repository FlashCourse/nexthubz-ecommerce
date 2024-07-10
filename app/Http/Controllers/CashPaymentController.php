<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CashPaymentController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
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

        return view('cash-payment', compact('cartData', 'orderData'));
    }

    public function pay(Request $request)
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

            // Retrieve order items related to the order
            $orderItems = OrderItem::where('order_id', $order->id)->get();

            // Create a new payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'invoice_id' => 'NHL-INV#' . Str::random(6),
                'transaction_id' => Str::uuid(),
                'amount' => $order->total,
                'total' => $order->total,
                'currency' => 'BDT', // or any other currency
                'payment_method' => 'cash', // initial payment method
                'status' => 'pending',
                'invoice_date' => Carbon::now(),
            ]);

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

        session()->put('order_success', true);
        Mail::to($request->user())->send(new OrderPlaced($order, $payment, $orderItems));
        return redirect()->route('order-success');
    }
}
