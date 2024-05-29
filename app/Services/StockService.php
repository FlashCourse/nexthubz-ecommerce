<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function deductStock(array $productQuantities)
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

    public function restoreStock(int $orderId)
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
