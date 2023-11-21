<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultsController
{
    const unit_measure = [
        ['value' => 'each', 'name' => 'Each', 'weighed' => 0],
        ['value' => 'kg', 'name' => 'Kg', 'weighed' => 1],
        ['value' => 'ton', 'name' => 'Tons', 'weighed' => 1],
        ['value' => 'bag', 'name' => 'Bag', 'weighed' => 0],
        ['value' => 'liter', 'name' => 'Liters', 'weighed' => 0],
        ['value' => '5l', 'name' => '5L', 'weighed' => 0],
        ['value' => '20l', 'name' => '20L', 'weighed' => 0],
        ['value' => '200l', 'name' => '200L Drum', 'weighed' => 0],

    ];

    const unit_measure_weighed = [
        'each' => 0,
        'kg' => 1,
        'ton' => 1,
        'bag' => 0,
        'liter' => 0,
        '5l' => 0,
        '20l' => 0,
        '200l' => 0,

    ];

    const roles = [
        'workshop' => [
            ['value' => "manager", 'name' => 'Manager'],
            ['value' => "clerk", 'name' => 'Clerk'],
            ['value' => "buyer", 'name' => 'Buyer'],
            ['value' => "supervisor", 'name' => 'Supervisor'],
            ['value' => "mechanic", 'name' => 'Mechanic'],
            ['value' => "ws_inspector", 'name' => 'Inspector'],
            ['value' => "user", 'name' => 'Driver'],
        ],
        'manufacture' => [
            ['value' => "manager", 'name' => 'Manager'],
            //['value' => "supervisor", 'name' => 'Supervisor'],
            ['value' => "recipe", 'name' => 'Recipe'],
            ['value' => "clerk", 'name' => 'Clerk'],
            ['value' => "dispatch", 'name' => 'Dispatch'],
        ]
    ];
}
