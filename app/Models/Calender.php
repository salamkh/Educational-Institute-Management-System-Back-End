<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    use HasFactory;
    protected $table='calender';
    protected $primaryKey='calenderId';
    public $timestamps=false;
    protected $fillable = [
        'calenderId',
        'date',
        'note',
        'time',
            ];




}
