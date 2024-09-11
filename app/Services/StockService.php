<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StockService
{
    public function checkAvailability($cart)
    {
        $productIds = array_column($cart, 'product_id');
        $products = DB::table('products')
            ->whereIn('id', $productIds)
            ->select('id', 'name', 'stock')
            ->get()
            ->keyBy('id');

        $stockErrors = [];
        $stockAvailable = true;

        foreach ($cart as $item) {
            $productId = $item['product_id'];
            $requestedQuantity = $item['quantity'];
            $product = $products->get($productId);

            if (!$product) {
                $stockAvailable = false;
                $stockErrors[$productId] = 'Product not found.';
            } elseif ($product->stock < $requestedQuantity) {
                $stockAvailable = false;
                $stockErrors[$productId] = 'Insufficient stock.';
            }
        }

        return [$stockAvailable, $stockErrors];
    }
}
