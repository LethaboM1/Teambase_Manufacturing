<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureProductTransactions;

class UtilsController extends Controller
{
    function view()
    {
        $page_title = "System Utilities";
        return view('system.utils',[
            'page_title'=>$page_title
        ]);        
    }

    function transfer_header_products_to_lines()
    {
        /* $dispatches = DB::connection('twistedtest2_teambase')->table('twistedtest2_teambase.manufacture_jobcard_product_dispatches')
            ->where(function ($query) {
            $query->where('job_id','<>','0')
                ->orWhere('customer_id','<>','0')
                ->orWhere('product_id','<>','0')
                ->orWhere('manufacture_jobcard_product_id','<>','0');
        });  Test connection*/
        $dispatches = ManufactureJobcardProductDispatches::
            where(function ($query) {
            $query->where('job_id','<>','0')
                ->orWhere('customer_id','<>','0')
                ->orWhere('product_id','<>','0')
                ->orWhere('manufacture_jobcard_product_id','<>','0');
        });

        $lines_counter = 0;
        $header_counter = 0;
        $skipped_counter = 0;

        if(count($dispatches->get())==0){
            return back()->with('alertMessage', count($dispatches->get()).' Headers found to process.');
        }

        foreach ($dispatches->get() as $dispatch) {
            $form_fields = [];
            //Check if Header contains product
            if($dispatch->product_id != '0'||$dispatch->manufacture_jobcard_product_id != '0'){
                //Product is contained in Header
                $form_fields = [
                    'product_id' => $dispatch->product_id,
                    'manufacture_jobcard_product_id' => $dispatch->manufacture_jobcard_product_id,
                    'dispatch_id' => $dispatch->id,
                    'type_id' => $dispatch->id,
                    'qty' => number_format(Functions::negate($dispatch->qty),3),                        
                    'dispatch_temp' => $dispatch->dispatch_temp,
                    'user_id' => $dispatch->weight_out_user_id,                    
                    'reference_number' => $dispatch->reference,
                    'weight_in' => $dispatch->weight_in,
                    'weight_in_datetime' => $dispatch->weight_in_datetime,
                    'weight_in_user' => $dispatch->weight_in_user_id,
                    'registration_number' => ($dispatch->registration_number == null ? '':$dispatch->registration_number),
                    'status' => $dispatch->status,
                    'weight_out' => $dispatch->weight_out,
                    'weight_out_user' => $dispatch->weight_out_user_id,
                    'weight_out_datetime' => $dispatch->weight_out_datetime,                    
                    'updated_at' => $dispatch->weight_out_datetime,
                    'created_at' => $dispatch->weight_out_datetime,
                    'comment' => 'undo'                    
                ];

                if($dispatch->customer_id > 0){
                    $form_fields['type'] = 'CDISP';
                } elseif($dispatch->job_id > 0){
                    $form_fields['type'] = 'JDISP';                    
                }
                
                               
                if($form_fields['dispatch_id']>0 && ($form_fields['product_id']>0 || $form_fields['manufacture_jobcard_product_id']>0)){
                    $lines_counter++;                    
                    // Insert Product in Lines
                    $inserted_transaction = ManufactureProductTransactions::insertGetId($form_fields);
                    // $inserted_transaction = 1;
                    if($inserted_transaction>0){
                        //Clear Header Product
                        //dd('delete header'); 
                        $fields_update = ['customer_id'=>'0',
                            'job_id'=>'0',
                            'product_id'=>'0',
                            'manufacture_jobcard_product_id'=>'0'
                        ];
                        if (DB::connection('twistedtest2_teambase')->table('twistedtest2_teambase.manufacture_jobcard_product_dispatches')
                        ->where('id', $dispatch->id)->update($fields_update)){
                            $header_counter++;
                        }                                                
                    } else {                        
                        $fields_skipped = ['comment'=>'check_skipped_insert_failed'];
                        if (DB::connection('twistedtest2_teambase')->table('twistedtest2_teambase.manufacture_jobcard_product_dispatches')
                        ->where('id', $dispatch->id)->update($fields_skipped)){
                            $skipped_counter++;
                        }
                    }
                } 
                
            } else {
                $fields_skipped = ['comment'=>'check_skipped_product_id_blanks'];
                if (DB::connection('twistedtest2_teambase')->table('twistedtest2_teambase.manufacture_jobcard_product_dispatches')
                ->where('id', $dispatch->id)->update($fields_skipped)){
                    $skipped_counter++;
                }
            }
            
            
        }
        //dd('counter:'.$counter);
        return back()->with('alertMessage', 'Inserted '.$lines_counter.' Products to Lines. Updated '.$header_counter.' Headers. '.$skipped_counter.' skipped rows.');
    }

    
}
