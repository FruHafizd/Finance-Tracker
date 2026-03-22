<?php

namespace App\Livewire\Transactions;

use Livewire\Component;


class Export extends Component
{
    public $startDate;
    public $endDate;

    public function getExportUrl()
    {
        $this->errorMessage = '';
       if (! $this->startDate || ! $this->endDate) {
            throw new \Exception('Tanggal wajib diisi.');
        }

        if ($this->startDate > $this->endDate) {
            throw new \Exception('Tanggal awal tidak boleh lebih dari tanggal akhir.');
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
