<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureJobcardProducts extends Model
{
    use HasFactory;
    protected $table = 'manufacture_jobcard_products', $guard = [], $dates = ['updated_at', 'created_at'];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function jobcard()
    {
        return $this->hasOne(ManufactureJobcards::class, 'id', 'job_id')->first();
    }

    function product()
    {
        return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
    }

    function dispatches()
    {
        return $this->hasMany(ManufactureJobcardProductDispatches::class, 'manufacture_jobcard_product_id', 'id')->get();
    }

    function getQtyFilledAttribute()
    {
        $qty =  $this->dispatches()->sum('qty');
        // dd($qty);
        if (!is_numeric($qty)) $qty = 0;
        return round($qty, 3);
    }

    function getQtyDueAttribute()
    {
        return round($this->qty - $this->getQtyFilledAttribute(), 3);
    }
}
