<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Approvals extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'approvals', $primaryKey = 'id', $guard = [], $dates = ['updated_at', 'created_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i:s',
        'updated_at'  => 'datetime:Y-m-d H:i:s',        
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_type',
        'request_model',
        'request_model_id',
        'requesting_user_id',
        'approving_user_id',
        'approved',
        'declined',
        'created_at',
        'updated_at',
    ];

    

}
