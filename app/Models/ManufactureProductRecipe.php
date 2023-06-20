<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureProductRecipe extends Model
{
    use HasFactory;
    protected $table = 'manufacture_product_recipe', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function ManProduct()
    {
        return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
    }

    function product()
    {
        return $this->hasOne(ManufactureProducts::class, 'id', 'product_add_id')->first();
    }
}
