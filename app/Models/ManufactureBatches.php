<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManufactureBatches extends Model
{
    use HasFactory;
    protected $table = 'manufacture_batch', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function product()
    {
        return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
    }

    function dispatches()
    {
        //return $this->hasMany(ManufactureJobcardProductDispatches::class, 'batch_id', 'id')->get(); Obsolete 2023-11-10
    }

    function getQtyDispatchedAttribute()
    {
        //return $this->dispatches()->sum('qty'); Obsolete 2023-11-10
    }

    function getQtyLeftAttribute()
    {
        /* $dispatched = $this->getQtyDispatchedAttribute();
        $left = $this->qty - $dispatched;
        return $left; */ //Obsolete 2023-11-10
    }

    function scopeDueProduct($query)
    {
        /* $dispatched = $this->getQtyDispatchedAttribute();
        $left = $this->qty - $dispatched;
        $query->whereRaw("{$left} > 0"); */ //Obsolete 2023-11-10
    }
}
