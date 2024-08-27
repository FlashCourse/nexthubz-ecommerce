<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'user_id',
    ];

    /**
     * Get the promotion associated with the PromotionUser.
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Get the user who used the promotion.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
