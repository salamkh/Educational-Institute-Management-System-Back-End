<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class retired extends Model
{
    use HasFactory;
    protected $table = 'retiered';
    protected $primaryKey = '	rId';
    protected $fillable = [
        'retieredDate',
        'userId',
        'cause'
    ];
}
