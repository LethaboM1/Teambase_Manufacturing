<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureProducts extends Model
{
    use HasFactory;
    protected $table = 'manufacture_products', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function transactions()
    {
        return $this->hasMany(ManufactureProductTransactions::class, 'product_id', 'id');
    }


    function recipes()
    {
        return $this->hasMany(ManufactureProductRecipe::class, 'product_id', 'id');
    }

    function getQtyAttribute()
    {
        $qty = $this->transactions()->sum('qty');
        $qty = (!is_numeric($qty) ? 0 : $qty);

        return $qty;
    }
}
