<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductTransactions;
use App\Models\Plants;
use Illuminate\Http\Request;

use function PHPSTORM_META\elementType;

class DispatchController extends Controller
{

    //public $dispatch;

    function new()
    {
        return view('manufacture.dispatch.new');
    }

    function new_goods()
    {
        return view('manufacture.dispatch.goods-received');
    }

    function out_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {
        $error = false;

        $qty = $request->weight_out - $dispatch->weight_in;

        if ($qty == 0) {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Qty is Zero');
        }

        if (!Functions::validDate($request->weight_out_datetime, "Y-m-d\TH:i")) {
            $error = true;
            return back()->with('alertError', 'Invalid date time');
        }

        //$product_qty = $request->qty_due;
        $product_qty = $dispatch->jobcard_product()->qty_due;

        if ($product_qty < $qty) {
            $error = true;
            return back()->with('alertError', "Too much product. Due amount on this job card is {$product_qty}");
        }

        if (!$error) {
            $form_fields = [
                'weight_out' => $request->weight_out,
                'weight_out_datetime' => $request->weight_out_datetime,
                'weight_out_user_id' => auth()->user()->user_id,
                'qty' => $qty,
                'status' => 'Dispatched',
                'batch_id' => '0'
            ];



            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            if ($product_qty == $qty) {
                ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 1]);
            }

            if ($dispatch->jobcard_product()->product()->has_recipe == 0) {
                //Adjust transaction if no recipe
                dd('here');
            }

            //Close job card if filled 

            //Connie

        }
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

    function receiving_goods(Request $request)
    {
        $form_fields = $request->validate([
            "reference_number" => "required",
            "registration_number" => "required",
            "type_id" => "required|gt:0",
            "product_id" => "required|gt:0",
            "weight_in" => "required|gt:0",
        ]);


        $form_fields['type'] = 'REC';
        $form_fields['status'] = 'Pending';
        $form_fields['qty'] = 0;
        $form_fields['weight_in_user'] = auth()->user()->user_id;
        $form_fields['weight_in_datetime'] = date("Y-m-d\TH:i:s");


        ManufactureProductTransactions::insert($form_fields);
        return back()->with([
            'alertMessage' => 'Good receiving.',
            'tab' => 'receiving'
        ]);
    }

    function received_goods(Request $request, ManufactureProductTransactions $transaction)
    {
        $form_fields = $request->validate([
            "weight_out" => 'required|gt:0',
            "comment" => 'nullable'
        ]);

        if ($form_fields['weight_out'] > $transaction->weight_in) return back()->with(['alertError' => 'Truck weighs more than when weighed in.', 'tab' => 'receiving']);
        $qty = $transaction->weight_in - $form_fields['weight_out'];

        $form_fields['weight_out_user'] = auth()->user()->user_id;
        $form_fields['weight_out_datetime'] = date("Y-m-d\TH:i:s");
        $form_fields['status'] = 'Completed';
        $form_fields['qty'] = $qty;

        ManufactureProductTransactions::where('id', $transaction->id)->update($form_fields);
        return back()->with([
            'alertMessage' => 'Good Received.',
            'tab' => 'receiving'
        ]);
    }
}
