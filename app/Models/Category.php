<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }


}
