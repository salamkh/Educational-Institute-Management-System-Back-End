<?php

namespace App\Exports;

use App\Models\timeMonitoring;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class timeMonitoringExport implements FromCollection, WithHeadings 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $collection;

    public function __construct($arrays)
    {

        $this->collection = collect($arrays);
    }

    public function collection()
    {
        return $this->collection;
    }
    public function headings(): array
    {
        return [
            'رقم تعريف المستخدم',
            'الاسم',
            'الدخول',
            'الخروج',
            'التاريخ'
        ];
    }
}
