<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public function render()
    {
        return view('livewire.home.index')->layout('layouts.app', ['title' => 'Beranda']);
    }
}