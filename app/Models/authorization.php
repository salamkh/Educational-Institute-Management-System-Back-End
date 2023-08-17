<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class authorization extends Model
{
    use HasFactory;
    
    use HasFactory;
    protected $table = 'authorization';
    protected $primaryKey = 'aId';
    protected $fillable = [
        'name',
    ];
}
