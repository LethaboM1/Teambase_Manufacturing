<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    function ready()
    {
        return view('manufacture.dispatch.ready');
    }

    function orders()
    {
        return view('manufacture.dispatch.orders');
    }
}
