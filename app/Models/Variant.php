<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'image',
        'regular_price',
        'sale_price',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttribute::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically generate SKU when a variant is being created
        static::creating(function ($variant) {
            // Generate SKU if not provided
            if (empty($variant->sku)) {
                $variant->sku = self::generateUniqueSku();
            }
        });
    }

    /**
     * Generate a unique SKU using a prefix and UUID for the variant.
     *
     * @return string
     */
    private static function generateUniqueSku()
    {
        $prefix = 'VAR'; // Set your desired prefix here
        $uuid = (string) Str::uuid(); // Generate a UUID

        // Use only the first 8 characters of the UUID to keep the SKU manageable
        $uniquePart = substr($uuid, 0, 8);

        return strtoupper($prefix . '-' . $uniquePart);
    }
}
