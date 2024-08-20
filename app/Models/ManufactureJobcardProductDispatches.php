<?php

namespace App\Models;

use App\Http\Controllers\Functions;
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
    protected $guarded = ['id'];

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

    function customer_weighed_product()
    {
        /* if ($this->transactions() !== null) {
            return $this->transactions()->customer_product();
        } */
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

        return $this->hasOne(ManufactureProductTransactions::class, 'dispatch_id', 'id')->first();
        
    }

    function linked_transactions()
    {
        return $this->hasMany(ManufactureProductTransactions::class, 'dispatch_id', 'id')->get();
    }

    function linked_transactions_filtered($id)
    {
        return $this->hasMany(ManufactureProductTransactions::class, 'dispatch_id', 'id')->where('product_id', $id)->get();
    }

    function linked_transactions_filtered_joined($id)
    {
        return $this->hasMany(ManufactureProductTransactions::class, 'dispatch_id', 'id')->where('product_id', $id)->get();
    }

    function distinct_jobs()
    {
        return $this->hasMany(ManufactureJobcards::class, 'id', 'job_id')->distinct()->select('jobcard_number')->get();
    }    
    
    function accum_tonnage($date){
        //Gets accummulated tonnage for Job by weighed item across all Dispatches for date
        // $accum_tonnage_products = [];        
        $date = strtotime($date);
        $date = date('Y-m-d', $date);
        //Get JC Products
        $products = $this->jobcard()->products()->get();
       
        $weighed_product_transactions = [];
        foreach($products as $product => $value){            
            //is Product Weighed
            $product_accum = 0.00;
            if ($products[$product]->product()->weighed_product == '1'){
                
                //Do we have transactions in Date Range
                if (count($value->ordered_transactions()->where('weight_out_datetime', '>=', $date.' 00:00:01')->where('weight_out_datetime', '<=', $date.' 23:59:59'))>0){
                    
                    $transactions = $value->ordered_transactions()->where('weight_out_datetime', '>=', $date.' 00:00:01')->where('weight_out_datetime', '<=', $date.' 23:59:59');
                    
                    foreach($transactions as $transaction){                        
                        $product_accum = $product_accum + Functions::negate($transaction->qty);
                        $weighed_product_transactions[$transaction->dispatch_id]=['dispatch_id'=>$transaction->dispatch_id,
                            'job_id'=>$transaction->dispatch()->job_id,
                            'product_id'=>$transaction->product_id,
                            'qty'=>Functions::negate($transaction->qty),
                            'accum_qty'=>$product_accum,
                        ];
                        // array_push($weighed_product_transactions,[$transaction->dispatch_id=>[$transaction->product_id=>['dispatch_id'=>$transaction->dispatch_id,
                        //     'job_id'=>$transaction->dispatch()->job_id,
                        //     'product_id'=>$transaction->product_id,
                        //     'qty'=>Functions::negate($transaction->qty),
                        //     'accum_qty'=>$product_accum,
                        // ]]]);                      
                    }    
                }
            };          
            
        }
        
        if(key_exists($this->id, $weighed_product_transactions)){return $weighed_product_transactions[$this->id];} else 
        return 0;

    }

}

