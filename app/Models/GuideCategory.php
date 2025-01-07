<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuideCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'order'
    ];

    public function guides(): HasMany
    {
        return $this->hasMany(Guide::class)->orderBy('order');
    }
}
