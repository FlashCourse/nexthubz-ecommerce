<?php
namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockService {
    public function deductStock(array $productQuantities)
    {
        DB::transaction(function () use ($productQuantities) {
            $productIds = array_keys($productQuantities);
            $products = Product::whereIn('id', $productIds)->get();

            if ($products->count() != count($productIds)) {
                return redirect()->route('checkout')->with('error', 'One or more products could not be found.');
            }

            $bulkUpdateArray = [];
            foreach ($products as $product) {
                $productId = $product->id;
                $quantityToDeduct = $productQuantities[$productId];
                $newStock = $product->stock - $quantityToDeduct;

                if ($newStock < 0) {
                    return redirect()->route('checkout')->with('error', 'Insufficient stock for some product');
                }

                $bulkUpdateArray[$productId] = $newStock;
            }

            // Perform a bulk update using a CASE statement in raw SQL
            $caseStatements = collect($bulkUpdateArray)->map(function ($newStock, $productId) {
                return "WHEN $productId THEN $newStock";
            })->join(' ');

            Product::whereIn('id', array_keys($bulkUpdateArray))
                  ->update(['stock' => DB::raw("CASE id $caseStatements END")]);
        });
    }

    public function restoreStock(int $orderId)
    {
        DB::transaction(function () use ($orderId) {
            // Retrieve order items and sum the quantities
            $orderItemQuantities = OrderItem::where('order_id', $orderId)
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->pluck('total_quantity', 'product_id')->toArray();

            if (empty($orderItemQuantities)) {
                return; // No items found, nothing to restore
            }

            // Prepare the bulk update SQL using CASE statement
            $caseStatements = collect($orderItemQuantities)->map(function ($quantity, $productId) {
                return "WHEN $productId THEN stock + $quantity";
            })->join(' ');

            // Perform the bulk update on the products
            Product::whereIn('id', array_keys($orderItemQuantities))
                  ->update(['stock' => DB::raw("CASE id $caseStatements END")]);
        });
    }
}