<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        return redirect()->route('order-success');
    }
}
