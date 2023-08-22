<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ManufactureJobcardProductDispatches extends Model
{
    use HasFactory;
    protected $table = 'manufacture_jobcard_product_dispatches', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function jobcard_product()
    {
        return $this->hasOne(ManufactureJobcardProducts::class, 'id', 'manufacture_jobcard_product_id')->first();
    }

    function plant()
    {
        if ($this->plant_id > 0) {
            return $this->hasOne(Plants::class, 'plant_id', 'plant_id')->first();
        }
    }

    function driver()
    {
        return $this->hasOne(User::class, 'user_id', '')->first();
    }

    function jobcard()
    {
        return $this->jobcard_product()->jobcard();
    }

    function product()
    {
        return $this->jobcard_product()->product();
    }
}
