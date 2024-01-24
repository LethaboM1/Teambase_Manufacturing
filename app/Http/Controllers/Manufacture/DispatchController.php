<?php

namespace App\Http\Controllers\Manufacture;

use App\Models\Plants;
use Illuminate\Http\Request;
use App\Models\ManufactureBatches;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Http\Controllers\Controller;
use App\Models\ManufactureCustomers;
use function PHPSTORM_META\elementType;

use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;

class DispatchController extends Controller
{

    //public $dispatch;
    public $listeners = ['transfer_dispatch'];

    function new()
    {
        return view('manufacture.dispatch.new');
    }

    function new_goods()
    {
        return view('manufacture.dispatch.goods-received');
    }

    function delete_dispatch(ManufactureJobcardProductDispatches $dispatch)
    {
        if ($dispatch !== null && $dispatch->id > 0) {
            ManufactureProductTransactions::where('dispatch_id', $dispatch->id)->delete();
            $dispatch->delete();
        }

        return back()->with('alertMessage', 'Dispatch deleted!');
    }

    function out_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {

        $error = false;

        if ($dispatch->weight_in > 0) {
            if ($dispatch->weight_in > $request->weight_out) return back()->with('alertError', 'your weight is lower than when weighed in.');
        }

        if ($request->customer_dispatch == 0) {
            //check non-weight or weight



            if ($dispatch->weight_in == 0) {
                $form_fields = $request->validate([
                    "job_id" => "required|exists:manufacture_jobcards,id",
                    //"manufacture_jobcard_product_id" => "required|exists:manufacture_jobcard_products,id", Moved to Lines 2023-11-13
                    "delivery_zone" => "required",
                    "reference" => 'nullable',
                    //'qty' => 'required:gt:0' Moved to Lines 2023-11-14
                ]);
            } else {
                $form_fields = $request->validate([
                    "job_id" => "required|exists:manufacture_jobcards,id",
                    //"manufacture_jobcard_product_id" => "required|exists:manufacture_jobcard_products,id", Moved to Lines 2023-11-13
                    "delivery_zone" => "required",
                    "reference" => 'nullable',
                    'dispatch_temp' => 'required|gt:-1',
                    // 'weight_in' => 'required:gt:0'
                ]);
            }

            //Get Jobcard(s) on this Dispatch and Check if AllFilled=True to Close.
            // $manufacture_jobcard_product_id = ManufactureProductTransactions::where('dispatch_id', $dispatch->id)->first();

            $jobcards = ManufactureJobcards::where('id', $form_fields['job_id'])->get();
            foreach ($jobcards as $jobcard) {

                //Close job card if all filled

                if (ManufactureJobcardProducts::where('job_id', $jobcard->id)->where('filled', '0')->count() == 0) {

                    ManufactureJobcards::where('id', $jobcard->id)->update(['status' => 'Completed']);
                }
            }
            //***** $jobcard = ManufactureJobcardProducts::where('id', $form_fields['manufacture_jobcard_product_id'])->first();            

            /*$product_qty = $jobcard->qty_due; */ //Moved to Lines 2023-11-14 

            $job = ManufactureJobcards::select('delivery_address')->where('id', $form_fields['job_id'])->first();
            $form_fields['delivery_address'] = $job->delivery_address;
        } elseif ($request->customer_dispatch == 1) {
            //check non-weight or weight
            if ($dispatch->weight_in == '0') {
                $form_fields = $request->validate([
                    "customer_id" => "required|exists:manufacture_customers,id",
                    //"product_id" => "required|exists:manufacture_products,id", Moved to Lines 2023-11-14
                    "delivery_zone" => "required",
                    "reference" => 'nullable',
                    //'qty' => 'required:gt:0' Moved to Lines 2023-11-14
                ]);
                //$product_qty = $request->qty; Moved to Lines 2023-11-14
            } else {
                $form_fields = $request->validate([
                    "customer_id" => "required|exists:manufacture_customers,id",
                    //"product_id" => "required|exists:manufacture_products,id", Moved to Lines 2023-11-14
                    "delivery_zone" => "required",
                    "reference" => 'nullable',
                    'dispatch_temp' => 'required|gt:-1',
                    //'weight_in' => 'required:gt:0' Moved to Lines 2023-11-14
                ]);
                // $product_qty = $request->weight_out - $dispatch->weight_in; Moved to Lines 2023-11-14
            }

            $customer = ManufactureCustomers::select('address')->where('id', $form_fields['customer_id'])->first();
            $form_fields['delivery_address'] = $customer->address;
        }

        if ($dispatch->weight_in == 0) {
            //$qty = $request->qty; Moved to Lines 2023-11-14
            $dispatch_temperature = 0;
        } else {
            //$qty = $request->weight_out - $dispatch->weight_in; Moved To Lines 2023-11-14
            $dispatch_temperature = $request->dispatch_temp;
        }


        if ($dispatch_temperature < 0 || $dispatch_temperature == '') {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Dispatch Temperature cannot be blank.');
        }

        //dd($request);
        if (!$error) {
            if ($request->customer_dispatch == 0) {

                if ($dispatch->weight_in == 0) {
                    $form_fields = [
                        // "job_id" => $form_fields['job_id'],
                        //"manufacture_jobcard_product_id" => $form_fields['manufacture_jobcard_product_id'], Moved to Lines 2023-11-14
                        "reference" => ($form_fields['reference'] == null ? "" : $form_fields['reference']),
                        "delivery_zone" => $form_fields['delivery_zone'],
                        'weight_out' => 0,
                        'weight_out_datetime' => date("Y-m-d\TH:i"),
                        'weight_out_user_id' => auth()->user()->user_id,
                        //'qty' => $request->qty, Moved to Lines 2023-11-14
                        'status' => 'Dispatched'
                    ];
                } else {

                    $form_fields = [
                        // "job_id" => $form_fields['job_id'],
                        //"manufacture_jobcard_product_id" => $form_fields['manufacture_jobcard_product_id'], Moved to Lines 2023-11-14
                        "reference" => ($form_fields['reference'] == null ? "" : $form_fields['reference']),
                        "delivery_zone" => $form_fields['delivery_zone'],
                        'weight_out' => $request->weight_out,
                        'weight_out_datetime' => date("Y-m-d\TH:i"),
                        'weight_out_user_id' => auth()->user()->user_id,
                        //'qty' => $qty, Moved to Lines 2023-11-14
                        'status' => 'Dispatched'
                    ];
                }
            } elseif ($request->customer_dispatch == 1) {
                if ($dispatch->weight_in == '0') {
                    $form_fields = [
                        // "job_id" => $form_fields['job_id'],
                        "customer_id" => $form_fields['customer_id'],
                        //"product_id" => $form_fields['product_id'], Moved to Lines 2023-11-14
                        "reference" => ($form_fields['reference'] == null ? "" : $form_fields['reference']),
                        "delivery_zone" => $form_fields['delivery_zone'],
                        'weight_out' => '0',
                        'weight_out_datetime' => date("Y-m-d\TH:i"),
                        'weight_out_user_id' => auth()->user()->user_id,
                        //'qty' => $request->qty, Moved to Lines 2023-11-14
                        'status' => 'Dispatched'
                    ];
                } else {
                    $form_fields = [
                        // "job_id" => $form_fields['job_id'],
                        "customer_id" => $form_fields['customer_id'],
                        //"product_id" => $form_fields['product_id'], Moved to Lines 2023-11-14
                        "reference" => ($form_fields['reference'] == null ? "" : $form_fields['reference']),
                        "delivery_zone" => $form_fields['delivery_zone'],
                        'weight_out' => $request->weight_out,
                        'weight_out_datetime' => date("Y-m-d\TH:i"),
                        'weight_out_user_id' => auth()->user()->user_id,
                        //'qty' => $qty, Moved to Lines 2023-11-14
                        'status' => 'Dispatched'
                    ];
                }
            }

            $form_fields['dispatch_temp'] = $dispatch_temperature;

            ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

            $form_fields = ['status' => 'Dispatched'];
            ManufactureProductTransactions::where('dispatch_id', $dispatch->id)->update($form_fields);


            return back()->with(['alertMessage' => "Dispatch No. {$dispatch->dispatch_number} is now Out for Delivery", 'print_dispatch' => $dispatch->id]);
        }
    }

    function add_dispatch(Request $request)
    {
        $form_fields = $request->validate([
            "weight_in" => 'gt:0',
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

        $form_fields['status'] = 'Loading';

        $form_fields['weight_in_user_id'] = auth()->user()->user_id;
        $form_fields['weight_in_datetime'] = date("Y-m-d\TH:i");

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

    function return_goods(Request $request)
    {

        $form_fields = $request->validate([
            "registration_number" => "required",
            "type_id" => "required|gt:0",
            "product_id" => "required|gt:0",
            "qty" => "required|gt:0",
        ]);


        $form_fields['type'] = 'RET';
        $form_fields['status'] = 'Completed';
        $form_fields['user_id'] = auth()->user()->user_id;
        $form_fields['qty'] = Functions::negate($form_fields['qty']);

        $return_id = ManufactureProductTransactions::insertGetId($form_fields);
        return back()->with([
            'alertMessage' => 'Goods returned.',
            'tab' => 'receiving',
            'print_return' => $return_id
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
            // 'print_receipt' => $transaction->id
        ]);
    }

    function print_dispatch(ManufactureJobcardProductDispatches $dispatch)
    {
        //dd($dispatch);
        $dispatch_lines = [];
        $dispatch_lines = ManufactureProductTransactions::select(
            DB::raw('(select code from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as code'),
            DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_product_transactions.product_id) as description'),
            'qty'
        )
            ->where('dispatch_id', $dispatch->id)
            ->get()
            ->toArray();

        $pdf = "<table style=\"width: 760px; border-collapse: collapse; table-layout: fixed;\"> 
                    <tr>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\">Dispatch Note</th>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\">No.{$dispatch['dispatch_number']}</th>
                    </tr> 
                </table>
                <br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>";
        if ($dispatch->customer_id == 0) {
            //Jobcard Dispatch
            // dd($dispatch->jobcard());
            $pdf .= "<td style=\"width: 50%; padding:5px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Contractor:</strong> " . $dispatch->jobcard()->contractor . "</td>
                            <td style=\"width: 50%; padding:5px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Date:</strong> {$dispatch['created_at']}</td>
                        </tr>
                        <tr>
                            <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none;border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Address:</strong> " . (strlen($dispatch->delivery_address) > 0 ? $dispatch->delivery_address : $dispatch->jobcard()->delivery_address) . "</td>
                            <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; border-bottom: none;padding-left: 5px; padding-bottom: 5px;\"><strong>Site Number:</strong>{$dispatch->jobcard()->site_number}</td>
                        </tr> ";
        } else {
            //Customer Dispatch
            $pdf .= "<td style=\"width: 50%; padding:5px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Customer:</strong> " . (ucfirst($dispatch->customer()->name)) . "</td>
                            <td style=\"width: 50%; padding:5px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Date:</strong> {$dispatch['created_at']}</td>
                        </tr>
                        <tr>
                            <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none;border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Address:</strong> " . (strlen($dispatch->delivery_address) > 0 ? $dispatch->delivery_address : $dispatch->customer()->address) . " </td>
                            <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; border-bottom: none;padding-left: 5px; padding-bottom: 5px;\"><strong>Site Number:</strong> Not Applicable </td>
                        </tr> ";
        }
        $pdf .= "<tr>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none;border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Zone:</strong>" . ($dispatch->delivery_zone == null || $dispatch->delivery_zone == 0 ? "N/A" : "{$dispatch->delivery_zone}") . "</td>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; border-bottom: none;padding-left: 5px; padding-bottom: 5px;\"><strong>Ref:</strong> {$dispatch->reference} </td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none;border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Dispatch Temp:</strong>" . ($dispatch->dispatch_temp !== null ? "{$dispatch->dispatch_temp} C" : "N/A") . "</td>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; border-bottom: none;padding-left: 5px; padding-bottom: 5px;\">
                            " . ($dispatch->plant_id > 0 ? "<strong>Plant Number:</strong>" . $dispatch->plant()->plant_number . ", Reg:" . $dispatch->plant()->reg_number : $dispatch->registration_number) . "
                        </td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed In by:</strong>" . ($dispatch->weigh_in_user() !== NULL ? "{$dispatch->weigh_in_user()->name} {$dispatch->weigh_in_user()->last_name}" : "") . "</td>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none;border-bottom: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed Out by:</strong>" . ($dispatch->weigh_out_user() !== NULL ? "{$dispatch->weigh_out_user()->name} {$dispatch->weigh_out_user()->last_name}" : "") . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none;border-bottom: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed In date:</strong> {$dispatch->weight_in_datetime} </td>
                        <td style=\"width: 50%; padding:5px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none;border-bottom: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed Out date:</strong> {$dispatch->weight_out_datetime} </td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; border-bottom: none;padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed In:</strong> {$dispatch->weight_in} </td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none;border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed Out:</strong> {$dispatch->weight_out} </td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Nett Weight:</strong> {$dispatch->qty} </td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"></td>
                    </tr>
                </table>
                <br><br>
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
                    <tbody>";


        /* if($dispatch->customer_id == '0'){
                                //Jobcard Dispatch - references to batches
                                $pdf .= "<tr><td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->product()->code}</td>
                                <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->product()->description}</td>";
                            }
                            else {
                                //Jobcard Dispatch - references to products
                                $pdf .= "<tr><td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->customer_product()->code}</td>
                                <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->customer_product()->description}</td>";
                            }    
                                
                            $pdf .="<td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->qty}</td>
                        </tr>"; */
        //Multiple Lines if they exist from Transactions 2023-11-15
        if ($dispatch->product_id > 0 && $dispatch->qty > 0) {
            $pdf .= "<tr>
                        <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->product_()->code}</td>
                        <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->product_()->description}</td>
                        <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch->qty}</td>
                    </tr>";
        }

        foreach ($dispatch_lines as $dispatch_line) {
            $pdf .= "<tr>";
            $pdf .= "<td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch_line['code']}</td>
                                    <td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">{$dispatch_line['description']}</td>";
            $pdf .= "<td style=\"font-weight: normal; font-size: 13px; text-align: left; padding: 10px;\">" . \App\Http\Controllers\Functions::negate($dispatch_line['qty']) . "</td>";
            $pdf .= "</tr>";
        }


        $pdf .= "</tbody>
                </table>
                <br><br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 30%;font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; padding-left: 5px; padding-top: 5px; height:25px;\"><strong>Name:</strong></td>
                        <td style=\"width: 70%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\">" . ($dispatch->weigh_out_user() !== NULL ? "{$dispatch->weigh_out_user()->name} {$dispatch->weigh_out_user()->last_name}" : "_______________________________________") . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Date:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">" . ($dispatch->weight_out_datetime !== NULL ? "{$dispatch->weight_out_datetime}" : "_______________________________________") . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Emplyee No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">" . ($dispatch->weigh_out_user() !== NULL ? "{$dispatch->weigh_out_user()->employee_number}" : "_______________________________________") . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Contact No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-top: none; padding-left: 5px; padding-bottom: 5px; height:25px;\"><strong>Signature:</strong> </td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\">_______________________________________</td>
                    </tr>
                </table>
                <br><br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 30%;font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; padding-left: 5px; padding-top: 5px; height:25px;\"><strong>Driver Name:</strong></td>
                        <td style=\"width: 70%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Date:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Emplyee No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Contact No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-top: none; padding-left: 5px; padding-bottom: 5px; height:25px;\"><strong>Signature:</strong> </td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\">_______________________________________</td>
                    </tr>
                </table>
                <br><br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 30%;font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; padding-left: 5px; padding-top: 5px; height:25px;\"><strong>Receivers Name:</strong></td>
                        <td style=\"width: 70%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Date:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Emplyee No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Contact No:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-top: none; padding-left: 5px; padding-bottom: 5px; height:25px;\"><strong>Signature:</strong> </td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\">_______________________________________</td>
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

        Functions::printPDF($pdf, 'receipt-' . $dispatch->id, false, true, 'P', 'A4');
    }

    function print_return(ManufactureProductTransactions $transaction)
    {
        if ($transaction->type != 'RET') return;

        $pdf = "<table style=\"width: 760px; border-collapse: collapse; table-layout: fixed;\"> 
                    <tr>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\"># {$transaction['id']}</th>
                        <th style=\"width: 50%; font-weight: bold; font-size: 20px; text-align: left; border: none;\">Return To Supplier</th>
                    </tr> 
                </table>
                <br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 50%;padding:10px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Supplier:</strong> " . ucfirst($transaction->supplier()['name']) . "</td>
                        <td style=\"width: 50%;padding:10px; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\"><strong>Reg No.:</strong> " . ucfirst($transaction['registration_number']) . "</td>
                    </tr>
                    <tr>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>User:</strong> {$transaction->user()->name} </td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\">&nbsp;</td>
                    </tr>
                </table>
                <br><br>
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
                <br><br>
                <table style=\"width: 750px; border-collapse: collapse; table-layout: fixed;\">
                    <tr>
                        <td style=\"width: 30%;font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; padding-left: 5px; padding-top: 5px; height:25px;\"><strong>Driver Name:</strong></td>
                        <td style=\"width: 70%;font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; padding-left: 5px; padding-top: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-bottom: none; border-top: none; padding-left: 5px; height:25px;\"><strong>Date:</strong></td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-bottom: none; border-top: none; padding-left: 5px;\">_______________________________________</td>
                    </tr>
                    <tr>
                        <td style=\"width: 30%; font-weight: normal; font-size: 13px; text-align: right; border: 1.5px solid rgb(39, 39, 39); border-right: none; border-top: none; padding-left: 5px; padding-bottom: 5px; height:25px;\"><strong>Signature:</strong> </td>
                        <td style=\"width: 70%; font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\">_______________________________________</td>
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
        // dd($transaction);
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
        // dd($transaction);
    }
}
