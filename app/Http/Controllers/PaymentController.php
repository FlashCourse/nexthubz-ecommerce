<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{

    protected function processOrder($addressData, $orderData, $cartItems)
    {
        DB::beginTransaction();

        try {
            $address = $this->createOrUpdateAddress($addressData);
            $order = $this->createOrder($address->id, $orderData);
            $this->processStock($cartItems);
            $this->createOrderItems($order, $cartItems);
            $payment = $this->createPayment($order);

            DB::commit();

            return [
                'order' => $order,
                'payment' => $payment,
                'orderItems' => OrderItem::where('order_id', $order->id)->get(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    // PROCESS ORDER HELPER FUNCTIONS

    protected function createOrUpdateAddress($addressData)
    {
        $user = Auth::user();
        return Address::updateOrCreate(
            ['user_id' => $user ? $user->id : null], // Use null for guest checkout
            $addressData
        );
    }

    protected function createOrder($addressId, $orderData)
    {
        $user = Auth::user();
        return Order::create(array_merge($orderData, [
            'user_id' => $user ? $user->id : null,
            'address_id' => $addressId,
            'status' => 'pending',
        ]));
    }

    protected function createOrderItems($order, $cartItems)
    {
        $orderItemsData = array_map(function ($item) use ($order) {
            return [
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'variant_id' => $item['variant_id'] ?? null,
            ];
        }, $cartItems);
        OrderItem::insert($orderItemsData);
    }

    protected function processStock($cartItems)
    {
        $productQuantities = array_map(function ($item) {
            return [
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity']
            ];
        }, $cartItems);
        $this->deductStock($productQuantities);
    }

    protected function createPayment($order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'invoice_id' => 'NHL-INV#' . Str::random(6),
            'transaction_id' => Str::uuid(),
            'amount' => $order->total,
            'total' => $order->total,
            'currency' => 'BDT',
            'payment_method' => 'cash',
            'status' => 'pending',
            'invoice_date' => Carbon::now(),
        ]);
    }


    protected function getSessionData()
    {
        return [
            'addressData' => session()->get('addressData', []),
            'orderData' => session()->get('orderData', []),
            'cartItems' => session()->get('cartData', [])
        ];
    }

    protected function clearSessionData()
    {
        session()->forget(['addressData', 'orderData', 'cartData']);
        session()->put('order_success', true);
    }

    protected function sendOrderEmail($user, $result)
    {
        Mail::to($user)->send(new OrderPlaced($result['order'], $result['payment'], $result['orderItems']));
    }



    // ------------------STOCK MANAGEMENT-------------------
    protected function  deductStock(array $productQuantities)
    {
        DB::transaction(function () use ($productQuantities) {
            $bulkProductUpdateArray = [];
            $bulkVariantUpdateArray = [];

            foreach ($productQuantities as $item) {
                if (isset($item['variant_id'])) {
                    $variant = Variant::find($item['variant_id']);
                    if (!$variant || $variant->stock < $item['quantity']) {
                        return redirect()->route('checkout')->with('error', 'Insufficient stock for some product variant');
                    }
                    $bulkVariantUpdateArray[$item['variant_id']] = $variant->stock - $item['quantity'];
                } else {
                    $product = Product::find($item['product_id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        return redirect()->route('checkout')->with('error', 'Insufficient stock for some product');
                    }
                    $bulkProductUpdateArray[$item['product_id']] = $product->stock - $item['quantity'];
                }
            }

            // Perform bulk update for products
            if (!empty($bulkProductUpdateArray)) {
                $productCaseStatements = collect($bulkProductUpdateArray)->map(function ($newStock, $productId) {
                    return "WHEN $productId THEN $newStock";
                })->join(' ');

                Product::whereIn('id', array_keys($bulkProductUpdateArray))
                    ->update(['stock' => DB::raw("CASE id $productCaseStatements END")]);
            }

            // Perform bulk update for variants
            if (!empty($bulkVariantUpdateArray)) {
                $variantCaseStatements = collect($bulkVariantUpdateArray)->map(function ($newStock, $variantId) {
                    return "WHEN $variantId THEN $newStock";
                })->join(' ');

                Variant::whereIn('id', array_keys($bulkVariantUpdateArray))
                    ->update(['stock' => DB::raw("CASE id $variantCaseStatements END")]);
            }
        });
    }

    protected function restoreStock(int $orderId)
    {
        DB::transaction(function () use ($orderId) {
            $orderItems = OrderItem::where('order_id', $orderId)->get();

            $bulkProductUpdateArray = [];
            $bulkVariantUpdateArray = [];

            foreach ($orderItems as $item) {
                if ($item->variant_id) {
                    $variant = Variant::find($item->variant_id);
                    if ($variant) {
                        $bulkVariantUpdateArray[$item->variant_id] = $variant->stock + $item->quantity;
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $bulkProductUpdateArray[$item->product_id] = $product->stock + $item->quantity;
                    }
                }
            }

            // Perform bulk update for products
            if (!empty($bulkProductUpdateArray)) {
                $productCaseStatements = collect($bulkProductUpdateArray)->map(function ($newStock, $productId) {
                    return "WHEN $productId THEN $newStock";
                })->join(' ');

                Product::whereIn('id', array_keys($bulkProductUpdateArray))
                    ->update(['stock' => DB::raw("CASE id $productCaseStatements END")]);
            }

            // Perform bulk update for variants
            if (!empty($bulkVariantUpdateArray)) {
                $variantCaseStatements = collect($bulkVariantUpdateArray)->map(function ($newStock, $variantId) {
                    return "WHEN $variantId THEN $newStock";
                })->join(' ');

                Variant::whereIn('id', array_keys($bulkVariantUpdateArray))
                    ->update(['stock' => DB::raw("CASE id $variantCaseStatements END")]);
            }
        });
    }
}
