<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProducts;
use App\Models\Plants;
use Illuminate\Http\Request;

use function PHPSTORM_META\elementType;

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

        //Check batch is ready to dispatch on Manufacturing
        $batch = ManufactureBatches::where('product_id', $jobcard_product->product_id)->where('status', 'Ready for dispatch')->first();
        //Check is dispathed Product is a Raw Product -> Has Recipe?
        $has_recipe = ManufactureProducts::select(['has_recipe'])->where('id', $jobcard_product->product_id)->first();

        //No Ready batch and is not Raw Product
        if ($batch == null && $has_recipe == '1') return back()->with('alertError', 'Could not find batch.');
        //No Ready batch but is Raw Product
        elseif ($batch == null && $has_recipe == '0') $form_fields['batch_id'] = '0';
        //Has ready batch and is not Raw Product
        elseif ($batch !== null) $form_fields['batch_id'] = $batch->id;        

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

    function return_dispatch(Request $request)
    {
        dd('Difference in weights will be returned', $request->toArray());
    }
}
