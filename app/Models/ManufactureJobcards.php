<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureJobcards extends Model
{
    use HasFactory;

    protected $table = 'manufacture_jobcards', $guard = [], $dates = ['updated_at', 'created_at'];

    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    function getUnfilledProductsAttribute()
    {
        $qty = $this->products()->where('filled', 0)->count();
        if (!is_numeric($qty)) dd("Not numeric Unfilled Products : {$qty}");
        return $this->products()->where('filled', 0)->count();
    }

    function products()
    {
        return $this->hasMany(ManufactureJobcardProducts::class, 'job_id', 'id');
    }
}
