<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guide extends Model
{
    protected $fillable = [
        'guide_category_id',
        'title',
        'slug',
        'content',
        'order'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($guide) {
            if (!$guide->slug) {
                $guide->slug = Str::slug($guide->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(GuideCategory::class, 'guide_category_id');
    }
}
