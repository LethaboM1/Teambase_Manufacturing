<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultsController
{
    const unit_measure = [
        ['value' => 'each', 'name' => 'Each'],
        ['value' => 'kg', 'name' => 'Kg'],
        ['value' => 'ton', 'name' => 'Tons'],
        ['value' => 'bag', 'name' => 'Bag'],
        ['value' => 'liter', 'name' => 'Liters'],
        ['value' => '5l', 'name' => '5L'],
        ['value' => '20l', 'name' => '20L'],
        ['value' => '200l', 'name' => '200L Drum'],

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
