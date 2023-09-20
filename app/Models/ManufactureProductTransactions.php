<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureProductTransactions extends Model
{
    use HasFactory;
    protected $table = 'manufacture_product_transactions', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'date:Y-m-d',
    ];

    function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id')->first();
    }

    function supplier()
    {
        if ($this->type == 'REC' || $this->type == 'RET') {
            return $this->hasOne(ManufactureSuppliers::class, 'id', 'type_id')->first();
        }
    }

    function product()
    {
        return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
    }
}
