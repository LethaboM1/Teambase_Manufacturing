<?php

namespace App\Models;

use App\Http\Controllers\Functions;
use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureJobcardProducts extends Model
{
    use HasFactory;
    protected $table = 'manufacture_jobcard_products', $guard = [], $dates = ['updated_at', 'created_at'];

    private $item_average, $counter;

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];



    public function scopeSearch($query, $searchTerm)
    {
        $term = "%" . $searchTerm . "%";
        return $query->whereIn('job_id', ManufactureJobcards::select('id as job_id')->search($term))
            ->whereIn('product_id', ManufactureProducts::select('id as product_id')->search($term));
    }

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
        return $this->hasMany(ManufactureJobcardProductDispatches::class, 'manufacture_jobcard_product_id', 'id')->get(); //2023-11-09 Moved to Transactions Table
        // return $this->hasMany(ManufactureProductTransactions::class, 'manufacture_jobcard_product_id', 'id')->get();
    }

    function weighed_transactions()
    {
        return $this->hasMany(ManufactureJobcardProductDispatches::class, 'manufacture_jobcard_product_id', 'id')->get(); //2023-11-09 Moved to Transactions Table
        //return $this->hasMany(ManufactureProductTransactions::class, 'manufacture_jobcard_product_id', 'id')->get();
    }

    function transactions()
    {
        //return $this->hasMany(ManufactureJobcardProductDispatches::class, 'manufacture_jobcard_product_id', 'id')->get(); 2023-11-09 Moved to Transactions Table
        return $this->hasMany(ManufactureProductTransactions::class, 'manufacture_jobcard_product_id', 'id')->get();
    }

    function getQtyFilledAttribute()
    {
        $qty =  $this->transactions()->sum('qty');
        $qty_dispatch =  $this->dispatches()->sum('qty');

        if (!is_numeric($qty)) $qty = 0;
        if (!is_numeric($qty_dispatch)) $qty_dispatch = 0;

        $qty = Functions::negate($qty);
        $qty = $qty + $qty_dispatch;
        return round($qty, 3);
    }

    function getQtyDueAttribute()
    {
        $qty = round($this->qty - $this->getQtyFilledAttribute(), 3);
        return $qty;
    }

    function getFilledItemPercentageAttribute()
    {
        $qty = round($this->qty, 3);
        $qty_due = round($this->qty - $this->getQtyFilledAttribute(), 3);
        $qty_filled = round($qty - $qty_due, 3);
        $filled_item_percentage = round(100 / $qty * $qty_filled, 3);
        // dd('qty:'.$qty.', filled:'.$qty_filled.', filled%:'.$filled_item_percentage);
        return $filled_item_percentage;
    }
    
}
