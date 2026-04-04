<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'limit_amount',
        'month',
        'year'
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('budgets.user_id', Auth::id());
            }
        });
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);    
    }

    public function spentAmount()
    {
        return Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');    
    }

    public function spentPercentage()
    {
        if ($this->limit_amount === 0) return 0;
        return round(($this->spentAmount() / $this->limit_amount) * 100, 1 );
    }
    
    public function isExceeded(): bool
    {
        return $this->spentAmount() >= $this->limit_amount;
    }
    public static function getExceededBudgets(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::with('category')
            ->where('user_id', $userId)
            ->where('month', (int) now()->format('n'))
            ->where('year', (int) now()->format('Y'))
            ->get()
            ->filter(fn($budget) => $budget->isExceeded());
    }

}
