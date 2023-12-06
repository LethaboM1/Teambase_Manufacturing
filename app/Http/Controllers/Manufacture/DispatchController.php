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

        // dd($request);
        /* $extra_items = [];
        $extra_items = ManufactureProductTransactions::select('id', 'dispatch_group_id', 'weight_in_datetime', 'product_id',
            DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_jobcard_product_dispatches.product_id) as description'),
            DB::raw('(select unit_measure from manufacture_products where manufacture_products.id= manufacture_jobcard_product_dispatches.product_id) as unit_measure'), 'qty')                
            ->where('dispatch_group_id', $dispatch->id)
            ->get()
            ->toArray(); */

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
                    'weight_in' => 'required:gt:0'
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

            $customer = ManufactureCustomers::select('address')->where('id', $form_fields['job_id']);
            $form_fields['delivery_address'] = $customer->address;
        }

        if ($dispatch->weight_in == 0) {
            //$qty = $request->qty; Moved to Lines 2023-11-14
            $dispatch_temperature = 0;
        } else {
            //$qty = $request->weight_out - $dispatch->weight_in; Moved To Lines 2023-11-14
            $dispatch_temperature = $request->dispatch_temp;
        }


        /* if ($qty <= 0) {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Qty is Zero');
        } */ //Moved to Lines 2023-11-14

        if ($dispatch_temperature < 0 || $dispatch_temperature == '') {
            $error = true;
            return back()->with('alertError', 'Cannot Complete Dispatch. Dispatch Temperature cannot be blank.');
        }

        /* if (!Functions::validDate($request->weight_out_datetime, "Y-m-d\TH:i")) {
            $error = true;
            return back()->with('alertError', 'Invalid date time');
        } 
        assigned in fields as timestamp 2023-09-13
        */

        // dd($product_qty.' '.$qty);
        /* if ($product_qty < $qty) {
            $error = true;
            return back()->with('alertError', "Too much product. Due amount on this job card is {$product_qty}");
        } */ //Moved to Lines 2023-11-14

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



            //dd($jobcard);

            //if($request->customer_dispatch == 0){
            /* if ($product_qty == $qty) {
                    ManufactureJobcardProducts::where('id', $jobcard->id)->update(['filled' => 1]);
                } */ //Moved to Lines 2023-11-15

            /* if ($jobcard->product()->has_recipe == 0) {
                    //Adjust transaction if no recipe
                    $form_fields = [
                        'product_id' => $jobcard->product_id,
                        'type' => 'JDISP',
                        'type_id' => $dispatch->id,
                        'qty' => -1 * ($qty),
                        'comment' => 'Dispatched on ' . $jobcard->jobcard()->jobcard_number,
                        'user_id' => auth()->user()->user_id,
                        //'registration_number' => $dispatch->plant()->reg_number,
                        // 'registration_number' => $dispatch->registration_number,
                        'status' => ' '
                    ];

                    if (isset($dispatch->plant()->reg_number)){
                        $form_fields['registration_number'] = $dispatch->plant()->reg_number;
                    } else {
                        $form_fields['registration_number'] = $dispatch->registration_number;
                    }

                    ManufactureProductTransactions::insert($form_fields);
                } */ //Moved to Lines 2023-11-15

            /* //Close job card if all filled 
                if (ManufactureJobcardProducts::where('job_id', $jobcard->jobcard()->id)->where('filled', '0')->count() == 0) {

                    ManufactureJobcards::where('id', $jobcard->jobcard()->id)->update(['status' => 'Completed']);
                } */
            //}

            // } elseif($request->customer_dispatch == 1){
            //     $product = ManufactureProducts::where('id', $form_fields['product_id'])->first();
            //     $customer = ManufactureCustomers::where('id', $form_fields['customer_id'])->first();
            //     if ($product->has_recipe == 0) {
            //         //Adjust transaction if no recipe
            //         $form_fields = [
            //             'product_id' => $form_fields['product_id'],
            //             'type' => 'CDISP',
            //             'type_id' => $dispatch->id,
            //             'qty' => -1 * ($qty),
            //             'comment' => 'Dispatched for ' . $customer->name,
            //             'user_id' => auth()->user()->user_id,
            //             //'registration_number' => $dispatch->plant()->reg_number,
            //             //'registration_number' => $dispatch->registration_number,
            //             'status' => ' '
            //         ];

            //         if (isset($dispatch->plant()->reg_number)){
            //             $form_fields['registration_number'] = $dispatch->plant()->reg_number;
            //         } else {
            //             $form_fields['registration_number'] = $dispatch->registration_number;
            //         }

            //         ManufactureProductTransactions::insert($form_fields);
            //     }
            // } //Moved to Lines 2023-11-15

            //Update Extra Items Lines IsCustomer? IsJobcard ProductTransaction? Status?
            // $extra_items = [];
            // $extra_items = ManufactureJobcardProductDispatches::select('id', 'dispatch_group_id', 'weight_in_datetime as the_date', 'manufacture_jobcard_product_dispatches.product_id as product_id',
            //     DB::raw('(select description from manufacture_products where manufacture_products.id= manufacture_jobcard_product_dispatches.product_id) as description'),
            //     DB::raw('(select unit_measure from manufacture_products where manufacture_products.id= manufacture_jobcard_product_dispatches.product_id) as unit_measure'), 'qty')                
            //     ->where('dispatch_group_id', $dispatch->id)
            //     ->get()
            //     ->toArray();

            //     foreach ($extra_items as $extra_item) {
            //         //dd($extra_item);
            //         //Update Dispatches
            //         $job_extra_line_item = ManufactureJobcardProducts::where('job_id', $jobcard->job_id)->where('product_id', $extra_item['product_id'])->first();

            //         $form_fields = [];
            //         if ($request->customer_dispatch == 1) {
            //             if($dispatch->weight_in == '0'){
            //                 $form_fields = [
            //                     "customer_id" => $request->customer_id,                            
            //                     "reference" => $request->reference,
            //                     "delivery_zone" => $request->delivery_zone,                            
            //                     'weight_out' => '0',
            //                     'weight_out_datetime' => date("Y-m-d\TH:i"),
            //                     'weight_out_user_id' => auth()->user()->user_id,                            
            //                     'status' => 'Dispatched'
            //                 ];
            //             } else {
            //                 $form_fields = [
            //                     "customer_id" => $request->customer_id,                            
            //                     "reference" => $request->reference,
            //                     "delivery_zone" => $request->delivery_zone,
            //                     'weight_out' => $request->weight_out,
            //                     'weight_out_datetime' => date("Y-m-d\TH:i"),                            
            //                     'weight_out_user_id' => auth()->user()->user_id,                            
            //                     'status' => 'Dispatched'
            //                 ];
            //             }


            //         } else {
            //             //Get Jobcard Line item (if any) for this extra line
            //             //dd('Job ID:'.$jobcard->job_id.' Product ID:'.$extra_item['product_id']);


            //             if($dispatch->weight_in == '0'){
            //                 $form_fields = [                                                        
            //                     "reference" => $request->reference,                                
            //                     "delivery_zone" => $request->delivery_zone,
            //                     'weight_out' => '0',
            //                     'weight_out_datetime' => date("Y-m-d\TH:i"),                            
            //                     'weight_out_user_id' => auth()->user()->user_id,                                
            //                     'status' => 'Dispatched'
            //                 ];                            
            //             } else {
            //                 $form_fields = [                                                        
            //                     "reference" => $request->reference,
            //                     "delivery_zone" => $request->delivery_zone,
            //                     'weight_out' => $request->weight_out,
            //                     'weight_out_datetime' => date("Y-m-d\TH:i"),                            
            //                     'weight_out_user_id' => auth()->user()->user_id,                                                            
            //                     'status' => 'Dispatched'
            //                 ];
            //             }
            //             if(isset($job_extra_line_item)){
            //                 $form_fields['manufacture_jobcard_product_id'] = $job_extra_line_item->id;
            //             }

            //         }                    

            //         ManufactureJobcardProductDispatches::where('id', $extra_item['id'])->update($form_fields);

            //         if($request->customer_dispatch == 0){                      

            //             if(isset($job_extra_line_item)){
            //                 $product_qty = $job_extra_line_item->qty_due;
            //                 if ($product_qty == $extra_item['qty']) {                                
            //                     ManufactureJobcardProducts::where('id', $job_extra_line_item->id)->update(['filled' => 1]);
            //                 }

            //             }
            //             $product = ManufactureProducts::where('id', $extra_item['product_id'])->first();

            //             if ($jobcard->has_recipe == 0) {
            //                 //Adjust transaction if no recipe
            //                 $form_fields = [
            //                     'product_id' => $extra_item['product_id'],
            //                     'type' => 'JDISP',
            //                     'type_id' => $extra_item['id'],
            //                     'qty' => -1 * ($extra_item['qty']),
            //                     'comment' => 'Dispatched on ' . $jobcard->jobcard()->jobcard_number,
            //                     'user_id' => auth()->user()->user_id,
            //                     //'registration_number' => $dispatch->plant()->reg_number,
            //                     // 'registration_number' => $dispatch->registration_number,
            //                     'status' => ' '
            //                 ];

            //                 if (isset($dispatch->plant()->reg_number)){
            //                     $form_fields['registration_number'] = $dispatch->plant()->reg_number;
            //                 } else {
            //                     $form_fields['registration_number'] = $dispatch->registration_number;
            //                 }

            //                 ManufactureProductTransactions::insert($form_fields);
            //             }

            //             //Close job card if all filled 
            //             if (ManufactureJobcardProducts::where('job_id', $jobcard->jobcard()->id)->where('filled', '0')->count() == 0) {

            //                 ManufactureJobcards::where('id', $jobcard->jobcard()->id)->update(['status' => 'Completed']);
            //             }

            //         } elseif($request->customer_dispatch == 1){
            //             $product = ManufactureProducts::where('id', $extra_item['product_id'])->first();
            //             //dd($form_fields['product_id']);
            //             $customer = ManufactureCustomers::where('id', $request->customer_id)->first();
            //             if ($product->has_recipe == 0) {
            //                 //Adjust transaction if no recipe
            //                 $form_fields = [
            //                     'product_id' => $extra_item['product_id'],
            //                     'type' => 'CDISP',
            //                     'type_id' => $extra_item['id'],
            //                     'qty' => -1 * ($extra_item['qty']),
            //                     'comment' => 'Dispatched for ' . $customer->name,
            //                     'user_id' => auth()->user()->user_id,                                
            //                     'status' => ' '
            //                 ];

            //                 if (isset($dispatch->plant()->reg_number)){
            //                     $form_fields['registration_number'] = $dispatch->plant()->reg_number;
            //                 } else {
            //                     $form_fields['registration_number'] = $dispatch->registration_number;
            //                 }

            //                 ManufactureProductTransactions::insert($form_fields);
            //             }
            //         }                        


            //     } //Moved to Lines 2023-11-15

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

    function return_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {

        // $error = false;

        // //dd('weight out:'.$dispatch->weight_out.' weight back:'.$request->weight_in);

        // $returnqty = $request->weight_in - $dispatch->weight_in;

        // if ($returnqty <= 0) {
        //     $error = true;

        //     return back()->with('alertError', 'Cannot Complete Dispatch Return. Qty is less than or equal to Zero');
        // }

        // //Compare what was dispatched with what is being returned
        // $product_qty = $dispatch->qty;

        // if ($product_qty < $returnqty) {
        //     $error = true;
        //     return back()->with('alertError', "Too much product. Amount dispatched on this Dispatch was {$product_qty}. You are trying to return {$returnqty}");
        // }

        // $newqty = $product_qty - $returnqty;

        // if (!$error) {
        //     $form_fields = [
        //         'qty' => $newqty
        //     ];

        //     if ($newqty > 0) {
        //         $form_fields['status'] = 'Partial Returned';
        //     } elseif ($newqty == 0) {
        //         $form_fields['status'] = 'Returned';
        //     }

        //     ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

        //     //If Jobcard Dispatch

        //     if ($dispatch->customer_id == '0'){
        //         //If Qty due after Dispatch Return is > 0 then set Product unfilled again
        //         if ($dispatch->jobcard_product()->qty_due > 0) {
        //             ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);
        //         }

        //         //Set job card as Open if filled <> 1
        //         if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

        //             ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
        //         }

        //         //Returned Raw Product Transaction
        //         if ($dispatch->jobcard_product()->product()->has_recipe == 0) {
        //             //Adjust transaction if no recipe
        //             $form_fields = [
        //                 'product_id' => $dispatch->jobcard_product()->product()->id,
        //                 // 'type' => 'CRETRN',
        //                 'type_id' => $dispatch->id,
        //                 'qty' => $returnqty,
        //                 // 'comment' => 'Returned for ' . $dispatch->customer()->name,
        //                 'user_id' => auth()->user()->user_id,
        //                 //'registration_number' => $dispatch->plant()->reg_number,
        //                 //'registration_number' => $dispatch->registration_number,
        //                 'status' => ' '
        //             ];

        //             if (isset($dispatch->plant()->reg_number)){
        //                 $form_fields['registration_number'] = $dispatch->plant()->reg_number;
        //             } else {
        //                 $form_fields['registration_number'] = $dispatch->registration_number;
        //             }

        //             //Jobcard Raw Material Return
        //             $form_fields['comment'] = 'Returned for ' . $dispatch->jobcard()->jobcard_number;
        //             $form_fields['type'] = 'JRETRN';                    

        //             ManufactureProductTransactions::insert($form_fields);
        //         }
        //     }
        //     else {
        //         //Returned Raw Product Transaction
        //         if ($dispatch->customer_product()->has_recipe == 0) {
        //             //Adjust transaction if no recipe
        //             $form_fields = [
        //                 'product_id' => $dispatch->customer_product()->id,
        //                 // 'type' => 'CRETRN',
        //                 'type_id' => $dispatch->id,
        //                 'qty' => $returnqty,
        //                 // 'comment' => 'Returned for ' . $dispatch->customer()->name,
        //                 'user_id' => auth()->user()->user_id,
        //                 //'registration_number' => $dispatch->plant()->reg_number,
        //                 //'registration_number' => $dispatch->registration_number,
        //                 'status' => ' '
        //             ];

        //             if (isset($dispatch->plant()->reg_number)){
        //                 $form_fields['registration_number'] = $dispatch->plant()->reg_number;
        //             } else {
        //                 $form_fields['registration_number'] = $dispatch->registration_number;
        //             }

        //             //Customer Raw Material Return
        //             $form_fields['comment'] = 'Returned for ' . $dispatch->customer()->name;
        //             $form_fields['type'] = 'CRETRN';                    

        //             ManufactureProductTransactions::insert($form_fields);
        //         }
        //     }

        //     if ($dispatch->customer_id == '0'){
        //         return back()->with('alertMessage', "Dispatch No. {$dispatch->dispatch_number} has been returned. Job {$dispatch->jobcard()->jobcard_number} has been credited with {$returnqty} {$dispatch->jobcard_product()->product()->description}");
        //     } else {
        //         return back()->with('alertMessage', "Dispatch No. {$dispatch->dispatch_number} has been returned. {$returnqty} {$dispatch->customer_product()->description} has been credited.");
        //     }

        // }
    }

    function transfer_dispatch(ManufactureJobcardProductDispatches $dispatch, Request $request)
    {

        // $error = false;

        // //dd($request);

        // if ($request->job_id !== null) {
        //     //Jobcard Transfer
        //     if ($dispatch->customer_id == '0'){
        //         $newjobcard = ManufactureJobcardProducts::where('job_id', $request->job_id)->where('filled', '0')->where('product_id', $dispatch->product()->id)->first();

        //     } else {
        //         $newjobcard = ManufactureJobcardProducts::where('job_id', $request->job_id)->where('filled', '0')->where('product_id', $dispatch->customer_product()->id)->first();
        //     }

        //     //dd($newjobcard);


        //     //blank jobcard or jobcard with unrelated products
        //     if ($request->job_id == 0) {
        //         $error = true;

        //         return back()->with('alertError', 'Please select a Jobcard to transfer to.');
        //     } else {
        //         if($dispatch->customer_id == '0'){
        //             if (ManufactureJobcardProducts::where('job_id', $request->job_id)->where('filled', '0')->where('product_id', $dispatch->product()->id)->count() == 0) {
        //                 $error = true;

        //                 return back()->with('alertError', 'Please select a Jobcard that contains matching product: "' . $dispatch->product()->description . '"');
        //             }
        //         } else {
        //             if (ManufactureJobcardProducts::where('job_id', $request->job_id)->where('filled', '0')->where('product_id', $dispatch->customer_product()->id)->count() == 0) {
        //                 $error = true;

        //                 return back()->with('alertError', 'Please select a Jobcard that contains matching product: "' . $dispatch->customer_product()->description . '"');
        //             }
        //         }
        //     }

        //     //Compare what was dispatched with what is being transfered

        //     if ($newjobcard->qty_due <= $dispatch->qty) {
        //         $filledqty = $newjobcard->qty_due;
        //     } else {
        //         $filledqty = $dispatch->qty;
        // }
        // } else {
        //     //Customer Transfer            
        //     $newcustomer = ManufactureCustomers::where('id', $request->customer_id)->first();
        //     $filledqty = $dispatch->qty;

        // }

        // //blank delivery zone
        // if ($request->delivery_zone == 0) {
        //     $error = true;

        //     return back()->with('alertError', 'Please select a valid Delivery Zone.');
        // }




        // // dd('due on new job:'.$newjobcard->qty_due.', transfer from old dispatch:'.$dispatch->qty. '. need to fill:'.$filledqty);

        // if (!$error) {
        //     $form_fields = [
        //         'qty' => 0,
        //         'status' => 'Transferred'
        //     ];

        //     //set this->dispatch qty to zero
        //     //credit this->jobcard with transfer qty
        //     ManufactureJobcardProductDispatches::where('id', $dispatch->id)->update($form_fields);

        //     if ($dispatch->customer_id == '0') {
        //         //set this->jobcard status if required after qty credit
        //         if ($dispatch->jobcard_product()->qty_due > 0) {
        //             ManufactureJobcardProducts::where('id', $dispatch->jobcard_product()->id)->update(['filled' => 0]);
        //         }

        //         if (ManufactureJobcardProducts::where('job_id', $dispatch->jobcard()->id)->where('filled', '0')->count() > 0) {

        //             ManufactureJobcards::where('id', $dispatch->jobcard()->id)->update(['status' => 'Open']);
        //         }
        //     }




        //     //Clone this->dispatch. Change JC, Delivery Zone based on this->request details
        //     $form_fields = [
        //         "dispatch_number" => $dispatch->dispatch_number,
        //         "reference" => $dispatch->reference,
        //         "delivery_zone" => $request->delivery_zone,
        //         "dispatch_temp" => $dispatch->dispatch_temp,
        //         "comment" => $dispatch->comment,
        //         "weight_in" => $dispatch->weight_in,
        //         "weight_in_user_id" => auth()->user()->user_id,
        //         "weight_in_datetime" => $dispatch->weight_in_datetime,
        //         "weight_out" => $dispatch->weight_out,
        //         "weight_out_user_id" => auth()->user()->user_id,
        //         "weight_out_datetime" => date("Y-m-d\TH:i"),
        //         "status" => 'Dispatched',
        //         "plant_id" => $dispatch->plant_id,
        //         "registration_number" => $dispatch->registration_number,
        //         // "manufacture_jobcard_product_id" => $newjobcard->id,
        //         "batch_id" => '0',
        //         "qty" => $filledqty,
        //     ];


        //     if($request->job_id !== null){
        //         //Jobcard Dispatch               
        //         $form_fields['manufacture_jobcard_product_id'] = $newjobcard->id;

        //         $form_fields['product_id'] = $dispatch->product_id;
        //         $form_fields['customer_id'] = 0;

        //     } else {
        //         //Customer Dispatch
        //         if($dispatch->customer_id == '0'){
        //                 //dd('jp:'.$dispatch->jobcard_product()->product()->id);
        //                 $form_fields['product_id'] =$dispatch->jobcard_product()->product()->id;} else {
        //                 //dd('pi:'.$dispatch->jobcard_product()->id);
        //                 $form_fields['product_id'] = $dispatch->product_id;}
        //         //$form_fields['product_id'] = $dispatch->product_id;
        //         $form_fields['customer_id'] = $request->customer_id;
        //     }         

        //     //dd($form_fields);
        //     $newdispatch_id = ManufactureJobcardProductDispatches::insertGetId($form_fields);

        //     if($request->job_id !== null){
        //         //Adjust status on clone->dispatch->Jobcard if filled
        //         /* if ($newjobcard->qty_due == 0) {
        //             ManufactureJobcardProducts::where('id', $newjobcard->id)->update(['filled' => 1]);
        //         } */ //Moved to Lines 2023-11-14

        //         if (ManufactureJobcardProducts::where('job_id', $request->job_id)->where('filled', '0')->count() == 0) {

        //             ManufactureJobcards::where('id', $request->job_id)->update(['status' => 'Completed']);
        //         }
        //     }

        //     //Adjust Raw Product Transactions
        //     $form_fields = [];
        //     if ($dispatch->customer_id == '0'){
        //         if ($dispatch->jobcard_product()->product()->has_recipe == 0) {

        //             //Jobcard Raw Material Return
        //             if($request->job_id !== null){
        //                     $form_fields['comment'] = 'Dispatched on ' . $newjobcard->jobcard()->jobcard_number;
        //                     $form_fields['type'] = 'JDISP';}
        //                  else {
        //                     $form_fields['comment'] = 'Dispatched for ' . $newcustomer->name;
        //                     $form_fields['type'] = 'CDISP';
        //                 } 
        //             $form_fields['type_id'] = $newdispatch_id;                                        

        //             ManufactureProductTransactions::where('type_id', $dispatch->id)->update($form_fields);
        //         } 
        //     } else {                
        //         if ($dispatch->customer_product()->has_recipe == 0) {

        //             //Jobcard Raw Material Return
        //             if($request->job_id !== null){
        //                 $form_fields['comment'] = 'Dispatched on ' . $newjobcard->jobcard()->jobcard_number;
        //                 $form_fields['type'] = 'JDISP';}
        //              else {
        //                 $form_fields['comment'] = 'Dispatched for ' . $newcustomer->name;
        //                 $form_fields['type'] = 'CDISP';
        //             }

        //             $form_fields['type_id'] = $newdispatch_id;


        //             ManufactureProductTransactions::where('type_id', $dispatch->id)->update($form_fields);
        //         }                 
        //     }



        //     //Print clone->dispatch
        //     //return back()->with(['alertMessage', "Jobcard {$dispatch->jobcard()->jobcard_number} has been transferred to Jobcard {$newjobcard->jobcard()->jobcard_number} on Dispatch No {$dispatch->dispatch_number}", 'print_dispatch' => $newdispatch_id]);

        //     return back()->with(['alertMessage', "Dispatch No {$dispatch->dispatch_number} has been transferred.", 'print_dispatch' => $newdispatch_id]);
        // }
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
                            " . ($dispatch->job_id > 0 ? "<strong>Plant Number:</strong>" . $dispatch->plant()->plant_number . ", Reg:" . $dispatch->plant()->reg_number : $dispatch->registration_number) . "
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
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed In:</strong> {$dispatch->weight_in} </td>
                        <td style=\"width: 50%;padding:10px;  font-weight: normal; font-size: 13px; text-align: left; border: 1.5px solid rgb(39, 39, 39); border-left: none; border-top: none; padding-left: 5px; padding-bottom: 5px;\"><strong>Weighed Out:</strong> {$dispatch->weight_out} </td>
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
