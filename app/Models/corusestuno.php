<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class corusestuno extends Model
{
    use HasFactory;
    protected $table = 'corusestuno';
    protected $primaryKey = 'id';
    protected $fillable = [
        'courseId',
        'number',
    ];
}
