<?php

namespace App\Services;

use App\Models\Discount;
use Carbon\Carbon;

class DiscountService
{
    /**
     * Apply a discount to the cart.
     *
     * @param array $cart
     * @param string|null $discountCode
     * @return array
     */
    public function applyDiscount($cart, $discountCode = null)
    {
        if (!$discountCode) {
            return ['No discount code provided.', 0];
        }

        $discount = Discount::where('name', $discountCode)->where('is_active', true)->first();

        if (!$discount) {
            return ['Invalid discount code.', 0];
        }

        if ($discount->start_date && Carbon::parse($discount->start_date)->isFuture()) {
            return ['This discount is not valid yet.', 0];
        }

        if ($discount->end_date && Carbon::parse($discount->end_date)->isPast()) {
            return ['This discount has expired.', 0];
        }

        $discountAmount = 0;
        foreach ($cart as &$item) {
            $item['discounted_price'] = $item['price']; // Reset discounted_price to original price

            $applicableProducts = is_string($discount->applicable_products)
                ? json_decode($discount->applicable_products, true)
                : $discount->applicable_products;

            if ($discount->applicable_products && !in_array($item['product_id'], $applicableProducts)) {
                continue;
            }

            if ($discount->discount_type == 'fixed') {
                $discountPerItem = $discount->discount_value / count($cart);
                $item['discounted_price'] -= $discountPerItem;
                $discountAmount += $discountPerItem;
            } elseif ($discount->discount_type == 'percentage') {
                $discountPerItem = ($item['price'] * $discount->discount_value / 100);
                $item['discounted_price'] -= $discountPerItem;
                $discountAmount += $discountPerItem;
            }
        }

        return ['Discount applied successfully!', $discountAmount];
    }
}
