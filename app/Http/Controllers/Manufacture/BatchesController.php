<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
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
        $form_fields = $request->validate([
            'product_id' => 'required|exists:manufacture_products,id',
            'qty' => 'required|gt:0'
        ]);
        $form_fields['status'] = 'Open';
        $form_fields['batch_number'] = Functions::get_doc_number('batch');
        if (strlen($form_fields['batch_number']) == 0) return back()->with('alertError', 'Coul dnot generate batch number.');

        $batch_id = ManufactureBatches::insertGetId($form_fields);
        return redirect("batch/{$batch_id}");
    }
}
