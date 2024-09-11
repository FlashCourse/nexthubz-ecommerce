<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'minimum_order_value',
        'start_date',
        'end_date',
        'usage_limit',
        'times_used',
        'is_active',
        'applicable_products',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'applicable_products' => 'array',
    ];

    /**
     * Get the usages of the coupon.
     */
    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today());
    }

    /**
     * Check if the coupon is still valid based on the current date and usage limits.
     */
    public function isValid()
    {
        return $this->is_active &&
            Carbon::today()->between($this->start_date, $this->end_date) &&
            ($this->usage_limit === null || $this->times_used < $this->usage_limit);
    }

    /**
     * Determine if the coupon is applicable to a given product.
     *
     * @param int $productId
     * @return bool
     */
    public function isApplicableToProduct(int $productId)
    {
        return in_array($productId, $this->applicable_products ?? []);
    }

    /**
     * Calculate the discount for a specific product based on the coupon's discount type.
     *
     * @param float $productPrice
     * @param int $productId
     * @return float
     */
    public function calculateDiscountForProduct(float $productPrice, int $productId)
    {
        if (!$this->isApplicableToProduct($productId)) {
            return 0; // No discount if the product is not applicable
        }

        if ($this->discount_type === 'percentage') {
            return $productPrice * ($this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed') {
            return min($this->discount_value, $productPrice); // Ensures fixed discount does not exceed product price
        }

        return 0;
    }

    /**
     * Accessor to format the start date.
     */
    public function getFormattedStartDateAttribute()
    {
        return Carbon::parse($this->start_date)->format('d-m-Y');
    }

    /**
     * Mutator to set the code in uppercase.
     */
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
}
