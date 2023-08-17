<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pricingPlan extends Model
{
    use HasFactory;
    protected $table = 'pricingplan';
    protected $primaryKey = 'planId';
    protected $fillable = [
        'planName'
    ];
}
