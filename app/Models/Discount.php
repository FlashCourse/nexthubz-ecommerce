<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
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
     * Scope a query to only include active discounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', Carbon::today())
            ->where('end_date', '>=', Carbon::today());
    }

    /**
     * Determine if the discount is applicable to a given product.
     */
    public function isApplicableToProduct($productId)
    {
        return in_array($productId, $this->applicable_products ?? []);
    }

    /**
     * Calculate the discount amount for a given order amount.
     */
    public function calculateDiscount($orderAmount)
    {
        if ($this->discount_type === 'percentage') {
            return $orderAmount * ($this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed') {
            return $this->discount_value;
        }

        return 0;
    }

    /**
     * Accessor to format the end date.
     */
    public function getFormattedEndDateAttribute()
    {
        return Carbon::parse($this->end_date)->format('d-m-Y');
    }
}
