<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufactureBatches;
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

    function batch_dispatch(ManufactureBatches $batch)
    {

        return view('manufacture.dispatch.dispatch', [
            'batch' => $batch
        ]);
    }
}
