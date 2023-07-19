<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureSettings;

class SelectLists extends Controller
{
    public const empty_select = ['name' => '...', 'value' => 0];

    public const batch_status_list = [
        ['name' => 'Open', 'value' => 'Open'],
        ['name' => 'In Production', 'value' => 'In Production'],
        ['name' => 'on Hold', 'value' => 'on Hold'],
        ['name' => 'Canceled', 'value' => 'Canceled'],
        ['name' => 'Ready for dispatch', 'value' => 'Ready for dispatch'],
    ];

    public const labs = [
        ['name' => 'Grading', 'value' => 'grading'],
        ['name' => 'Marshall Stability & Flow', 'value' => 'm-s-f'],
        ['name' => 'Max-viodless Density', 'value' => 'max-viodless-density'],
        ['name' => 'Road Test Core', 'value' => 'road-test-cores'],
    ];
}
