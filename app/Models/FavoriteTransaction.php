<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FavoriteTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'type',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', fn($q) => $q->where('user_id', Auth::id()));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
