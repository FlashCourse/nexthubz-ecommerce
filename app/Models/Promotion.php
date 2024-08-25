<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'code',
        'amount',
        'start',
        'end',
        'limit',
        'count',
        'active'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }
}
