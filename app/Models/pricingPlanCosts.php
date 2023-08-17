<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pricingPlanCosts extends Model
{
    use HasFactory;
    protected $table = 'plancosts';
    protected $primaryKey = 'planCostId';
    protected $fillable = [
        'max',
        'planId',
        'min',
        'cost'
    ];
}
