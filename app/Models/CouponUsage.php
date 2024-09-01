<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'times_used',
    ];

    /**
     * Get the coupon associated with the usage.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the user associated with the usage.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the usage.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Increment the usage count for the coupon usage.
     */
    public function incrementUsage()
    {
        $this->times_used++;
        $this->save();
    }

    /**
     * Check if a user has reached the maximum allowed usage for a coupon.
     */
    public function hasReachedUsageLimit()
    {
        return $this->times_used >= $this->coupon->usage_limit;
    }
}
