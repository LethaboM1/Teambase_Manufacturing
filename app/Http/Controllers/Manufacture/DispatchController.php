<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\Plants;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    function new()
    {
        return view('manufacture.dispatch.new');
    }

    function out_dispatch(Request $request)
    {
        dd($request);
    }

    function add_dispatch(Request $request)
    {

        $form_fields = $request->validate([
            "job_id" => "required|exists:manufacture_jobcards,id",
            "manufacture_jobcard_product_id" => "required|exists:manufacture_jobcard_products,id",
            "reference" => 'nullable',
            "haulier_code" => 'nullable',
            "weight_in_datetime" => 'required',
            "weight_in" => 'required|gt:0',
            "plant_id" => 'nullable',
            "registration_number" => 'nullable',
        ]);

        if (!isset($form_fields['registration_number']) && $form_fields['plant_id'] == 0) return back()->with('alertError', 'Plant/Reg No must be selected/filled.');

        if (isset($form_fields['registration_number'])) {
            $plant = $form_fields['registration_number'];
        } else {
            $plant = Plants::where('plant_id', $form_fields['plant_id'])->first();
            if ($plant == null) return back()->with('alertError', 'Plant not found.');
            $plant = $plant->toArray();
            $plant = "{$plant['plant_number']} {$plant['make']} {$plant['model']} {$plant['reg_number']}";
        }

        $jobcard_product = ManufactureJobcardProducts::where('id', $form_fields['manufacture_jobcard_product_id'])->first();
        if ($jobcard_product == null) return back()->with('alertError', 'Could not find job card.');

        $batch = ManufactureBatches::where('product_id', $jobcard_product->product_id)->where('status', 'Ready for dispatch')->first();
        if ($batch == null) return back()->with('alertError', 'Could not find batch.');


        $form_fields['batch_id'] = $batch->id;

        $form_fields['status'] = 'Loading';
        $form_fields['weight_in_user_id'] = auth()->user()->user_id;

        $form_fields['dispatch_number'] =  Functions::get_doc_number('dispatch');
        unset($form_fields['job_id']);

        ManufactureJobcardProductDispatches::insert($form_fields);

        return back()->with('alertMessage', "{$plant}, loading, Dispatch No. {$form_fields['dispatch_number']}");
    }

    function archive()
    {
        return view('manufacture.dispatch.archive');
    }

    function batch_dispatch(ManufactureBatches $batch)
    {

        return view('manufacture.dispatch.dispatch', [
            'batch' => $batch
        ]);
    }
}
