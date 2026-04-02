<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $casts = [
        'date' => 'date'
    ];

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'type',
        'date',
        'recurring_transactions_id',
        'category_id',
    ];
    
    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('transactions.user_id', Auth::id());
            }
        });  
    }

    public function user()  {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function account() 
    {
        return $this->belongsTo(Account::class);    
    }

}
