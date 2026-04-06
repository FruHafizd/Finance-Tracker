<?php

namespace App\Livewire\Home;

use App\Services\FinancialScoreService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FinancialScore extends Component
{
    public $scoreData;

    public function mount(FinancialScoreService $service)
    {
        $this->scoreData = $service->calculateCurrentMonthScore(Auth::id());
    }

    public function render()
    {
        return view('livewire.home.financial-score');
    }
}
