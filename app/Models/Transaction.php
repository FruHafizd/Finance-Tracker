<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()  {
        return $this->belongsTo(User::class);
    }

    public function recurring()
    {
        return $this->belongsTo(RecurringTransaction::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
