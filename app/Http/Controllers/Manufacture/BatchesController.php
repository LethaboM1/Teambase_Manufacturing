<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProductRecipe;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductTransactions;
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

        $product = ManufactureProducts::where('id', $form_fields['product_id'])->first();

        $form_fields['status'] = 'Open';
        $form_fields['batch_number'] = Functions::get_doc_number('batch');
        if (strlen($form_fields['batch_number']) == 0) return back()->with('alertError', 'Could not generate batch number.');

        $recipe = ManufactureProductRecipe::where('product_id', $form_fields['product_id'])->get();

        if ($recipe->count() == 0)  return back()->with('alertError', 'No recipe for this product. Create your recipe first.');

        $batch_id = ManufactureBatches::insertGetId($form_fields);

        foreach ($recipe as $item) {
            $qty = ($item->qty * $form_fields['qty']);

            ManufactureProductTransactions::insert([
                'product_id' => $item->product_add_id,
                'qty' => Functions::negate($qty),
                'user_id' => auth()->user()->user_id,
                'weight_out_user' => auth()->user()->user_id,
                'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                'status' => 'Completed',
                'type' => 'BAT',
                'type_id' => $batch_id,
                'comment' => "Used to manufacture {$product['code']} {$product['description']}"
            ]);
        }

        return redirect("batch/{$batch_id}");
    }
}
