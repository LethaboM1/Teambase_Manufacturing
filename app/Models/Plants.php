<?php

namespace App\Models;

use App\Models\ManufactureProductTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plants extends Model
{
    use HasFactory;
    protected $primaryKey = 'plant_id', $table = 'plants_tbl', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d',
    ];

    public function scopeSearch($query, $searchTerm)
    {
        $term = "%" . $searchTerm . "%";
        return $query->where('plant_number', 'like', $term)
            ->orWhere('make', 'like', $term)
            ->orWhere('model', 'like', $term)
            ->orWhere('reg_number', 'like', $term);
    }
}
