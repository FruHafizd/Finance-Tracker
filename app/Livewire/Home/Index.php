<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Transaction;

class Index extends Component
{
    public $startDate;
    public $endDate;
    public $chartData = [];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');
        $this->loadChartData();
    }

    public function updatedStartDate() { $this->loadChartData(); }
    public function updatedEndDate()   { $this->loadChartData(); }

    // ✅ Dengarkan event dari komponen transaksi manapun
    #[On('transaction-changed')]
    public function refreshChart()
    {
        $this->loadChartData(); // loadChartData sudah dispatch update-chart di dalamnya
    }

    private function loadChartData()
    {
        if (!$this->startDate || !$this->endDate || $this->startDate > $this->endDate) {
            $this->chartData = ['labels' => [], 'values' => [], 'colors' => []];
            $this->dispatch('update-chart', labels: [], values: [], colors: []);
            return;
        }

        $data = Transaction::selectRaw('category_id, SUM(amount) as total')
            ->where('type', 'expense')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->get();

        // 🔥 TAMPILKAN SEMUA (BUKAN TOP 5 DULU)
        $labels = $data->map(fn($t) => $t->category->name ?? 'Tidak Diketahui')->toArray();
        $values = $data->map(fn($t) => (float) $t->total)->toArray();
        $colors = $data->map(fn($t) => $t->category->color ?? '#6366f1')->toArray();

        $this->chartData = compact('labels', 'values', 'colors');

        $this->dispatch('update-chart',
            labels: $labels,
            values: $values,
            colors: $colors
        );
    }

    public function render()
    {
        return view('livewire.home.index')->layout('layouts.app', [
            'title' => 'Beranda'
        ]);
    }
}
