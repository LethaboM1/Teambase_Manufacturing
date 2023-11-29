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


    public function scopeSearch($query, $searchTerm)
    {
        $term = "%" . $searchTerm . "%";
        return $query->where('jobcard_number', 'like', $term)
            ->orWhere('contractor', 'like', $term)
            ->orWhere('site_number', 'like', $term)
            ->orWhere('contact_person', 'like', $term)
            ->orWhereRaw('REPLACE(contact_number," ","") like "' . str_replace(' ', '', $term) . '"')
            ->orWhereIn('customer_id', ManufactureCustomers::select('id as customer_id')->search($term));
    }

    function getUnfilledProductsAttribute()
    {
        $qty = $this->products()->where('filled', 0)->count();
        if (!is_numeric($qty)) dd("Not numeric Unfilled Products : {$qty}");
        return $this->products()->where('filled', 0)->count();
    }

    function customer()
    {
        if ($this->customer_id > 0) {
            return $this->hasOne(ManufactureCustomers::class, 'id', 'customer_id')->first();
        } else {
            return false;
        }
    }

    function products()
    {
        return $this->hasMany(ManufactureJobcardProducts::class, 'job_id', 'id');
    }
}
