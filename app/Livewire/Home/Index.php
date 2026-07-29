<?php

namespace App\Livewire\Home;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{

    public function mount()
    {
        //
    }


    public function render()
    {
        return view('livewire.home.index')
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}