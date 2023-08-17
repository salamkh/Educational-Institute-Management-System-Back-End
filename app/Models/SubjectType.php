<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model
{
    use HasFactory;
    protected $table='subject_type';
    protected $primaryKey='subjectTypeId';
    public $timestamps=false;
    protected $fillable = [
        'subjectTypeId',
        'subjectId',
        'typeId',


    ];

}
