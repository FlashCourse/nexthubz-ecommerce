<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_company',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'shipping_phone',
        'shipping_email',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'billing_phone',
        'billing_email',
        'subtotal',
        'tax',
        'shipping_cost',
        'total_discount',
        'due_amount',
        'paid_amount',
        'total',
        'status',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = self::generateOrderNumber();
        });
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $lastOrder = self::orderBy('id', 'desc')->first();
        $lastId = $lastOrder ? $lastOrder->id : 0;
        return $prefix . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT); // Example: ORD-000001
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }
}
