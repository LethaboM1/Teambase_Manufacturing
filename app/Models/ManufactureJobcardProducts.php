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
        return $this->hasOne(ManufactureJobcards::class, 'id', 'job_id');
    }

    // function transactions()
    // {
    //     return $this->hasMany(ManufactureProductTransactions::class, 'product_id', 'id');
    // }


    // function getQtyAttribute()
    // {
    //     return $this->transactions->sum('qty');
    // }
}
