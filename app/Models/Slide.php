<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Slide extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the plural of the model name
    protected $table = 'slides';

    // Specify the columns that are mass assignable
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_url',
        'active', // Include the 'active' field here
    ];

    /**
     * The "booted" method of the model.
     *
     * Apply a global scope to retrieve only active slides.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    /**
     * Scope a query to include inactive slides.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->withoutGlobalScope('active')->where('active', false);
    }

    /**
     * Scope a query to include all slides, both active and inactive.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllSlides($query)
    {
        return $query->withoutGlobalScope('active');
    }
}
