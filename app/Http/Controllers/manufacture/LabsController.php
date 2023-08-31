<?php

namespace App\Http\Controllers\Manufacture;

use Illuminate\Http\Request;
use App\Models\ManufactureBatches;
use App\Http\Controllers\Controller;
use App\Models\ManufactureBatchLabs;

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

        $request->validate([
            'sample.batch_id' => 'required|exists:manufacture_batch,id',
            'sample.batch_number' => 'required',
            'sample.sample' => 'required|gt:0',
            'sample.type' => 'required',
            'sample.datetime' => 'required|date'
        ]);

        $form_fields = $request->toArray();

        $date = date_create($form_fields['sample']['datetime']);
        $date = date_format($date, "Y-m-d");

        ManufactureBatchLabs::insert([
            'batch_id' => $form_fields['sample']['batch_id'],
            'date' => $date,
            'quantity' => $form_fields['sample']['sample'],
            'results' => base64_encode(json_encode($form_fields['sample']))
        ]);

        return back()->with('alerMessage', 'Sample was added!');
    }
}
