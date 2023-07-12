<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufactureBatches;
use Illuminate\Http\Request;

class LabsController extends Controller
{
    function list_batches()
    {
        return view('manufacture.lab.list-batches');
    }

    function view_batch(ManufactureBatches $batch)
    {
        return view('manufacture.lab.view-batch', [
            'batch' => $batch
        ]);
    }

    function add_lab(Request $request)
    {
        dd("add the lab test");
        dd($request);
    }
}
