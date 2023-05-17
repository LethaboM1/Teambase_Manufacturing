<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DefaultsController
{
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
            ['value' => "supervisor", 'name' => 'Supervisor'],
            ['value' => "clerk", 'name' => 'Clerk'],
            ['value' => "waybridge", 'name' => 'Waybridge'],
        ]
    ];
}
