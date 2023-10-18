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

    function getBatchQtyAttribute()
    {
        $debit = ManufactureBatches::where('product_id', $this->id)->sum('qty');
        $credit = ManufactureJobcardProductDispatches::where('product_id', $this->id)->sum('qty');
        $debit = (!is_numeric($debit) ? 0 : $debit);
        $credit = (!is_numeric($credit) ? 0 : $credit);

        $qty = $debit - $credit;

        return $qty;
    }
}
