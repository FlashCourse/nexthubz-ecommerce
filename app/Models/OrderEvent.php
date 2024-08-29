<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'event_type',
        'description',
    ];

    /**
     * Get the order that owns the event.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
