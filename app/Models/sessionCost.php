<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sessionCost extends Model
{
    use HasFactory;
    protected $table='sessioncost';
    protected $primaryKey='SCId';
    protected $fillable = [
        'sessionId',
        'cost',
        'studentNumber',
    ];
}
