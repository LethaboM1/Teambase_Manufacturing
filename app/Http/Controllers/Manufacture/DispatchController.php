<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureBatches;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcards;
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
        $dispatch_temperature = $request->dispatch_temp;

        if ($qty <= 0) {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Qty is Zero');
        }

        if ($dispatch_temperature <= 0 || $dispatch_temperature == '') {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Dispatch Temperature cannot be blank.');
        }

        /* if (!Functions::validDate($request->weight_out_datetime, "Y-m-d\TH:i")) {
            $error = true;
            return back()->with('alertError', 'Invalid date time');
        } 
        assigned in fields as timestamp 2023-09-13
        */

        $product_qty = $dispatch->jobcard_product()->qty_due;

        if ($product_qty < $qty) {
            $error = true;
            return back()->with('alertError', "Too much product. Due amount on this job card is {$product_qty}");
        }

        if (!$error) {
            $form_fields = [
                'weight_out' => $request->weight_out,
                'weight_out_datetime' => date("Y-m-d\TH:i"),
                'weight_out_user_id' => auth()->user()->user_id,
                'qty' => $qty,
                'status' => 'Dispatched'
            ];

            $form_fields['dispatch_temp'] = $dispatch_temperature;

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            if ($product_qty == $qty) {
                ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 1]);
            }

            if ($dispatch->jobcard_product()->product()->has_recipe == 0) {
                //Adjust transaction if no recipe
                $form_fields = [
                    'product_id' => $dispatch->jobcard_product()->product_id,
                    'type' => 'JDISP',
                    'type_id' => $dispatch->id,
                    'qty' => -1 * ($qty),
                    'comment' => 'Dispatched on ' . $dispatch->jobcard()->jobcard_number,
                    'user_id' => auth()->user()->user_id,
                    'registration_number' => $dispatch->plant()->reg_number,
                    'status' => ' '
                ];
                ManufactureProductTransactions::insert($form_fields);
            }

            //Close job card if all filled 
            if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() == 0) {

                ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Completed']);
            }

            return back()->with(['alertMessage' => "Dispatch No. {$dispatch->dispatch_number} is now Out for Delivery", 'print_dispatch' => $dispatch->id]);
        }
    }

    function add_dispatch(Request $request)
    {

        $form_fields = $request->validate([
            "job_id" => "required|exists:manufacture_jobcards,id",
            "manufacture_jobcard_product_id" => "required|exists:manufacture_jobcard_products,id",
            "reference" => 'nullable',
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
        $form_fields['weight_in_datetime'] = date("Y-m-d\TH:i");
        $form_fields['delivery_zone'] = $request->delivery_zone;

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

    function return_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {
                
        $error = false;

        // dd('weight out:'.$dispatch->weight_out.' weight back:'.$request->weight_in);

        $returnqty = $request->weight_in - $dispatch->weight_in;

        if ($returnqty <= 0) {
            $error = true;

            return back()->with('alertError', 'Cannot Complete Dispatch Return. Qty is less than or equal to Zero');
        }

        //Compare what was dispatched with what is being returned
        $product_qty = $dispatch->qty;

        if ($product_qty < $returnqty) {
            $error = true;
            return back()->with('alertError', "Too much product. Amount dispatched on this Dispatch was {$product_qty}. You are trying to return {$returnqty}");
        }

        $newqty = $product_qty - $returnqty;
        
        if (!$error) {
            $form_fields = [
                'qty' => $newqty];

            if($newqty > 0){
                $form_fields['status'] = 'Partial Returned';
            }
            elseif ($newqty == 0) {
                $form_fields['status'] = 'Returned';
            }

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            //If Qty due after Dispatch Return is > 0 then set Product unfilled again
            if ($dispatch->jobcard_product()->qty_due > 0) {
                ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);
            }            

            //Set job card as Open if filled <> 1
            if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

                ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
            }

            return back()->with('alertMessage', "Dispatch No. {$dispatch->dispatch_number} has been returned. Job {$dispatch->jobcard()->jobcard_number} has been credited with {$returnqty} {$dispatch->jobcard_product()->product()->description}");
        }
    }

    function transfer_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {
       dd('here 2023-09-14');         
        $error = false;

        // dd('weight out:'.$dispatch->weight_out.' weight back:'.$request->weight_in);

        $returnqty = $request->weight_in - $dispatch->weight_in;

        if ($returnqty <= 0) {
            $error = true;

            return back()->with('alertError', 'Cannot Complete Dispatch Return. Qty is less than or equal to Zero');
        }

        //Compare what was dispatched with what is being returned
        $product_qty = $dispatch->qty;

        if ($product_qty < $returnqty) {
            $error = true;
            return back()->with('alertError', "Too much product. Amount dispatched on this Dispatch was {$product_qty}. You are trying to return {$returnqty}");
        }

        $newqty = $product_qty - $returnqty;
        
        if (!$error) {
            $form_fields = [
                'qty' => $newqty];

            if($newqty > 0){
                $form_fields['status'] = 'Partial Returned';
            }
            elseif ($newqty == 0) {
                $form_fields['status'] = 'Returned';
            }

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            //If Qty due after Dispatch Return is > 0 then set Product unfilled again
            if ($dispatch->jobcard_product()->qty_due > 0) {
                ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);
            }            

            //Set job card as Open if filled <> 1
            if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {
                
                ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
            }

            return back()->with('alertMessage', "Dispatch No. {$dispatch->dispatch_number} has been returned. Job {$dispatch->jobcard()->jobcard_number} has been credited with {$returnqty} {$dispatch->jobcard_product()->product()->description}");
        }
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
        $form_fields['user_id'] = auth()->user()->user_id;


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
            'tab' => 'receiving',
            'print_receipt' => $transaction->id
        ]);
    }

    function print_dispatch(ManufactureJobcardProductDispatches $dispatch)
    {
        dd($dispatch);
    }

    function print_receipt(ManufactureProductTransactions $transaction)
    {

        $pdf = "<table style=\"width: 760px; border-collapse: collapse; table-layout: fixed;\"> 
                    <tr>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\">Receipt # {$transaction['id']}</th>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\">Reference No. # {$transaction['reference_number']}</th>
                    </tr> 
                </table>
                <br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 50%;padding:10px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Supplier:</strong> " . ucfirst($transaction->supplier()['name']) . "</td>
                        <td style=\"width: 50%;padding:10px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Reg No.:</strong> " . ucfirst($transaction['registration_number']) . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; border-top: none; padding-left: 5px;\"><strong>Weight In Date:</strong> " . $transaction['weight_in_datetime'] . "</td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\"><strong>Weight In:</strong>  {$transaction['weight_in']}</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weight Out Date: {$transaction['weight_out_datetime']}</strong> </td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weight Out:</strong> {$transaction['weight_out']}</td>
                    </tr>
                </table>
                <br>
                <table style=\"border-collapse: collapse; table-layout: fixed;\">
                    <thead>
                        <tr>
                            <th style=\"font-weight: bold; font-size: 16px; text-align: left; padding: 10px;\" colspan='3'>Product Details</th>
                        </tr>
                        <tr style=\"background-color: rgb(85, 85, 85);\">
                            <th style=\"width: 12%;font-weight: bold; font-size: 13px; color: #FFFFFF; text-align: left; padding: 10px;\">Code</th>
                            <th style=\"width: 78%;font-weight: bold; font-size: 13px; color: #FFFFFF; text-align: left; padding: 10px;\">Description</th>
                            <th style=\"width: 10%;font-weight: bold; font-size: 13px; color: #FFFFFF; text-align: left; padding: 10px;\">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$transaction->product()->code}</td>
                            <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$transaction->product()->description}</td>
                            <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$transaction->qty}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 50%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Supplier:</strong> " . ucfirst($transaction->supplier()['name']) . "</td>
                        <td style=\"width: 50%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Reg No.:</strong> " . ucfirst($transaction['registration_number']) . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; border-top: none; padding-left: 5px;\"><strong>Weight In Date:</strong> " . $transaction['weight_in_datetime'] . "</td>
                        <td style=\"width: 50%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\"><strong>Weight In:</strong>  {$transaction['weight_in']}</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weight Out Date: {$transaction['weight_out_datetime']}</strong> </td>
                        <td style=\"width: 50%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weight Out:</strong> {$transaction['weight_out']}</td>
                    </tr>
                </table>
                <br>
                <table style='width: 750px;'>
                    <tfoot>
                        <tr>
                        <td style='width: 100%; text-align: right; font-weight: bold; font-size: 11px;'>PL05 REV04 190524</td>
                        </tr>
                    </tfoot>
                </table>  ";

        Functions::printPDF($pdf, 'receipt-' . $transaction->id, false, true, 'P', 'A4');
        dd($transaction);
    }
}
