<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financialAccounts extends Model
{
    use HasFactory;
    protected $table = 'financialAccount';
    protected $primaryKey = 'FAId';
    protected $fillable = [
        'accountName',
        'status'
    ];
}
