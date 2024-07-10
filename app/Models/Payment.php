<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'invoice_id',
        'transaction_id',
        'amount',
        'total',
        'currency',
        'payment_method',
        'payment_date',
        'invoice_date',
        'status',
    ];


    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
