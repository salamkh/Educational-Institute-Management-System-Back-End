<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class UsersExport implements FromQuery, WithHeadings , WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'الرقم',
            'الاسم',
            'الدخول',
            'الخروج',
            'التاريخ'
        ];
    }
    public function map($user): array
    {
        return [
            $user->userId,
            $user->name
        ];
    }
    public function query()
    {
        return User::query();
    }
}
