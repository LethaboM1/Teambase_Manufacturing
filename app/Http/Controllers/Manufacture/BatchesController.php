<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcards;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
    function batches()
    {
        return view('manufacture.batches.list');
    }

    function create_batch()
    {
        return view('manufacture.batches.create-batch');
    }

    function view_batch($batch)
    {
        return view('manufacture.batches.view-batch', [
            'batch' => $batch
        ]);
    }

    function add_batch(Request $request)
    {
        $form_fields = $request->validate([]);


        $batch_id = ManufactureBatches::insertGetId($form_fields);
        return redirect("batch/{$batch_id}");
    }
}
