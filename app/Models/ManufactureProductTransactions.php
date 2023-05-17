<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureProductsTransactions extends Model
{
    use HasFactory;
    protected $table = 'manufacture_product_transactions', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'date:Y-m-d',
    ];
}
