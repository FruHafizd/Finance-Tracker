<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'color',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('categories.user_id', Auth::id());
            }
        });
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function budget()
    {
        return $this->hasOne(Budget::class);    
    }

    public function isSystem(): bool
    {
        return $this->is_system;
    }

    public function scopeSystem(Builder $query): Builder
    {
        return $query->where('is_system', true);
    }

    public function scopeUserOwned(Builder $query): Builder
    {
        return $query->where('is_system', false);
    }
}