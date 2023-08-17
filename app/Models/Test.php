<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $table='test';
    protected $primaryKey='testId';
    public $timestamps=false;
    protected $fillable = [
        'testId',
        'studentId',
        'sessionId',
        'teacherId',
        'value',
        'cause'
    ];

}
