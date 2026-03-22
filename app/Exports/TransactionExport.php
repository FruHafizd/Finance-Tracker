<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class TransactionExport implements FromArray, WithEvents, ShouldAutoSize
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function array(): array
    {
        $transactions = Transaction::with('category')
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$this->start, $this->end])
            ->get();

        $data = [];

        //Judul
        $data[] = ['LAPORAN TRANSAKSI'];
        $data[] = [
            'Periode: ' .
                 Carbon::parse($this->start)->locale('id')->isoFormat('DD-MMMM-YYYY') .
                ' s/d ' .
                Carbon::parse($this->end)->locale('id')->isoFormat('DD-MMMM-YYYY')
        ];
        $data[] = [];

        //Header
        $data[] = ['Tanggal', 'Kategori', 'Nama Transaksi','Tipe Transaksi', 'Jumlah'];

        $total = 0;

        foreach ($transactions as $item) {

            if ($item->type === 'income') {
                $total += $item->amount;
            }else {
                $total -= $item->amount;
            }
            $data[] = [
                Carbon::parse($item->date)->locale('id')->isoFormat('DD-MMMM-YYYY'),
                $item->category->name ?? '-',
                $item->name ?? '-',
                $item->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                $item->amount,
            ];
        }

        $data[] = [];

        $data[] = [
            '',
            '',
            '',
            'Total pengeluaran',
            $total,
        ];

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                // Hitung jumlah baris (biar dinamis)
                $lastRow = $sheet->getHighestRow();

                // Merge judul
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');

                // Bold + center judul
                $sheet->getStyle('A1:A2')->getFont()->setBold(true);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal('center');

                //Bold header
                $sheet->getStyle('A3:E3')->getFont()->setBold(true);

                // Format angka
               $sheet->getStyle("E4:E{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('[$Rp-421] #,##0');

                // Align kanan jumlah
                $sheet->getStyle("E5:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal('right');

                // Border semua tabel
                $sheet->getStyle("A3:E{$lastRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => 'thin',
                            ],
                        ],
                    ]);

                // Bold TOTAL
                $sheet->getStyle("D{$lastRow}:E{$lastRow}")
                    ->getFont()
                    ->setBold(true);
            },
        ];
    }
}
