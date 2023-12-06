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

    function weigh_in_user()
    {
        return $this->hasOne(User::class, 'user_id', 'weight_in_user_id')->first();
    }


    function weigh_out_user()
    {
        return $this->hasOne(User::class, 'user_id', 'weight_out_user_id')->first();
    }

    function driver()
    {
        return $this->hasOne(User::class, 'user_id', '')->first();
    }

    function jobcard()
    {
        return $this->hasOne(ManufactureJobcards::class, 'id', 'job_id')->first();
        // if ($this->transactions() !== null) {
        //     if ($this->transactions()->jobcard_id() !== null) {
        //         $jobcard = ManufactureJobcards::where('id', $this->transactions()->jobcard_id())->first();
        //         return $jobcard;
        //     }
        // }

        /* if ($this->jobcard_product() !== null) {
            return $this->jobcard_product();
        } */ //Obsolete 2023-11-15
    }

    function product()
    {
        // if ($this->transactions() !== null) {
        //     return $this->transactions()->jobcard_product();
        // }

        return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();;

        /* if ($this->jobcard_product() !== null) {
            return $this->jobcard_product()->product();
        } */
    }

    function customer()
    {
        if ($this->customer_id !== null) {
            return $this->hasOne(ManufactureCustomers::class, 'id', 'customer_id')->first();
        }
    }

    function product_()
    {
        if ($this->product_id !== null) {
            return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
        }
    }

    function customer_product()
    {
        if ($this->transactions() !== null) {
            return $this->transactions()->customer_product();
        }
        /*  if ($this->product_id !== null) {
            return $this->hasOne(ManufactureProducts::class, 'id', 'product_id')->first();
        } */
    }

    function transactions()
    {

        return $this->hasMany(ManufactureProductTransactions::class, 'dispatch_id', 'id')->first();
    }
}
