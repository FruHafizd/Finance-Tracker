<?php

namespace App\Livewire\Transactions;

use App\Exports\TransactionExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;


class Export extends Component
{
    public $startDate;
    public $endDate;
    public string $errorMessage = '';

    public function exportExcel()
    {
        $this->errorMessage = '';
        if (!$this->startDate || !$this->endDate) {
            $this->errorMessage = 'Tanggal wajib diisi';
            return;
            }

        if ($this->startDate > $this->endDate) {
            $this->errorMessage = 'Range tanggal salah';
            return;
        }

        return redirect()->route('export.excel', [
            'start' => $this->startDate,
            'end'   => $this->endDate,
        ]);
    }


    public function render()
    {
        return view('livewire.transactions.export');
    }
}
