<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $totalOmset;
    protected $jenisLaporan;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $totalOmset, $jenisLaporan, $startDate, $endDate)
    {
        $this->data = $data;
        $this->totalOmset = $totalOmset;
        $this->jenisLaporan = $jenisLaporan;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('admin.rekap.pdf', [
            'data' => $this->data,
            'totalOmset' => $this->totalOmset,
            'jenisLaporan' => $this->jenisLaporan,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'isExcel' => true
        ]);
    }
}