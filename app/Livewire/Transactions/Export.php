<?php

namespace App\Livewire\Transactions;

use Livewire\Component;


class Export extends Component
{
    public $startDate;
    public $endDate;
    public string $errorMessage = '';

    public function getExportUrl()
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

        return \URL::temporarySignedRoute(
            'export.excel',
            now()->addMinutes(5),
            [
                'start' => $this->startDate,
                'end'   => $this->endDate,
            ]
        );
    }


    public function render()
    {
        return view('livewire.transactions.export');
    }
}
