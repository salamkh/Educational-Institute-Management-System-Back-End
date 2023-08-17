<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subjectteacher extends Model
{
    use HasFactory;
    protected $table = 'subjectteacher';
    protected $primaryKey = 'stId';
}
