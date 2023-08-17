<?php

namespace App\Imports;

use App\Models\testy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestyImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new testy([
            'userId' => $row[0],
            'name'=> $row[1]
            ]);
    }
}
