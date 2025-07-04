<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufactureSettings extends Model
{
    use HasFactory;
    protected $table = 'manufacture_settings', $guard = [], $fillable = ['batch_number'];
}
