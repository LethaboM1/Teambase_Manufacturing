<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(ManufactureJobcardProductDispatches::class, 'batch_id', 'id')->get();
    }

    function getQtyDispatchedAttribute()
    {
        return $this->dispatches()->sum('qty');
    }

    function getQtyLeftAttribute()
    {
        $dispatched = $this->getQtyDispatchedAttribute();
        $left = $this->qty - $dispatched;
        return $left;
    }
}
