<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    public function applyCoupon($cart, $couponCode)
    {
        $coupon = Coupon::where('code', $couponCode)->first();
        if (!$coupon) {
            return ['Invalid coupon code.', 0];
        }

        if ($coupon->end_date && $coupon->end_date < now()) {
            return ['This coupon has expired.', 0];
        }

        if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit) {
            return ['This coupon has reached its usage limit.', 0];
        }

        $couponDiscount = 0; // Reset the discount amount

        foreach ($cart as &$item) {
            // Reset discounted_price to original price
            $item['discounted_price'] = $item['price'];

            $applicableProducts = is_string($coupon->applicable_products)
                ? json_decode($coupon->applicable_products, true)
                : $coupon->applicable_products;

            if ($coupon->applicable_products && !in_array($item['product_id'], $applicableProducts)) {
                continue;
            }

            if ($coupon->discount_type == 'fixed') {
                $discount = $coupon->discount_value / count($cart);
                $item['discounted_price'] -= $discount;
                $couponDiscount += $discount;
            } elseif ($coupon->discount_type == 'percentage') {
                $discount = ($item['price'] * $coupon->discount_value / 100);
                $item['discounted_price'] -= $discount;
                $couponDiscount += $discount;
            }
        }

        return ['Coupon applied successfully!', $couponDiscount];
    }
}
