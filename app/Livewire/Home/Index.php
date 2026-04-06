<?php

namespace App\Livewire\Home;

use App\Services\MonthInReviewService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $showReview = false;
    public $reviewData = [];

    public function mount(MonthInReviewService $service)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        $targetReviewMonth = $lastMonth->format('Y-m');

        // Trigger condition:
        // 1. Current day is between 1st and 7th (standard window)
        // 2. User hasn't seen the review for the target month
        if ($now->day >= 1 && $now->day <= 7 && $user->last_review_seen !== $targetReviewMonth) {
            $this->reviewData = $service->getReviewData($user->id);
            $this->showReview = true;
        }
    }

    public function markReviewAsSeen()
    {
        if (isset($this->reviewData['month_year'])) {
            $user = Auth::user();
            $user->last_review_seen = $this->reviewData['month_year'];
            $user->save();
        }
        
        $this->showReview = false;
    }

    public function render()
    {
        return view('livewire.home.index')
            ->layout('layouts.app', ['title' => 'Beranda']);
    }
}