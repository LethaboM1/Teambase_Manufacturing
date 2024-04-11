<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

// class DispatchReportExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         //
//     }
// }
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DispatchReportExport implements FromArray , WithHeadings, ShouldAutoSize
{
    protected $dispatches;

    public function __construct(array $dispatches)
    {        
        $this->dispatches = $dispatches;               
    }

    public function headings(): array
    {
        return array_keys($this->dispatches[0]);
    }

    public function array(): array
    {        
        return $this->dispatches;
    }
}
