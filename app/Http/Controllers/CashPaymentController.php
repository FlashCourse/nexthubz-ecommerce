<?php

namespace App\Http\Controllers;

use App\Jobs\CheckOrderStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CashPaymentController extends Controller
{
    public function index()
    {

        $cartData = session('cartData', []);
        $orderData = session('orderData', []);

        // Check if both cartData and orderData are empty
        if (empty($cartData) && empty($orderData)) {
            return redirect()->route('home');
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

            // Retrieve all product IDs from the cart items
            $productIds = array_column($cartItems, 'product_id');

            // Retrieve the quantities for all products
            $productQuantities = [];
            foreach ($cartItems as $item) {
                $productQuantities[$item['product_id']] = $item['quantity'];
            }

            // Retrieve all products to update
            $productsToUpdate = Product::whereIn('id', $productIds)->get();

            // Prepare the bulk update array
            $bulkUpdateArray = [];
            foreach ($productsToUpdate as $product) {
                $productId = $product->id;
                $quantity = $productQuantities[$productId] ?? 0; // Get quantity from cart items

                // Calculate the new stock value
                $newStock = max(0, $product->stock - $quantity); // Ensure stock doesn't go below 0

                // Check if the stock is not available
                if ($newStock < 0) {
                    return redirect()->route('checkout')->with('error', 'Sorry, the stock is not available for some products.');
                }

                // Add product ID and new stock value to bulk update array
                $bulkUpdateArray[$productId] = $newStock;
            }


            // Perform bulk update for all products
            Product::whereIn('id', array_keys($bulkUpdateArray))->update([
                'stock' => DB::raw('CASE id ' . implode(' ', array_map(function ($productId, $newStock) {
                    return "WHEN $productId THEN $newStock ";
                }, array_keys($bulkUpdateArray), $bulkUpdateArray)) . ' END')
            ]);

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
                $orderItemsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ];
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
