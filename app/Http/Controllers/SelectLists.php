<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureSettings;

class SelectLists extends Controller
{
    public const empty_select = ['name' => '...', 'value' => 0];

    public const zones_select = [
        ['name' => 'Zone A (0 to 30km)', 'value' => 'Zone A'],
        ['name' => 'Zone B (30 to 50km)', 'value' => 'Zone B'],
        ['name' => 'Zone C (50 to 70km)', 'value' => 'Zone C'],
        ['name' => 'Zone D (70 to 100km)', 'value' => 'Zone D'],
        ['name' => 'Zone E (> 100km)', 'value' => 'Zone E'],
    ];

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


/* 
insert into manufacture_jobcard_product_dispatch_deliveryzones set description='Zone A (0 to 30km)', code='Zone A';
insert into manufacture_jobcard_product_dispatch_deliveryzones set description='Zone B (30 to 50km)', code='Zone B';
insert into manufacture_jobcard_product_dispatch_deliveryzones set description='Zone C (50 to 70km)', code='Zone C';
insert into manufacture_jobcard_product_dispatch_deliveryzones set description='Zone D (70 to 100km)', code='Zone D';
insert into manufacture_jobcard_product_dispatch_deliveryzones set description='Zone E (> 100km)', code='Zone E';
*/