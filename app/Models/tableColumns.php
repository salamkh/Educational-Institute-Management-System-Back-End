<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tableColumns extends Model
{
    use HasFactory;
    protected $table = 'tablecolumns';
    protected $primaryKey = 'tableColId';
    protected $fillable = [
        'tableId',
        'arabicName',
        'EnglishName',
        'dataType'
    ];
}
