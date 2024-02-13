<?php

namespace App\Http\Controllers\Manufacture\Report;


use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;

use function PHPUnit\Framework\isNull;

class ManufactureReportsController extends Controller
{

    protected $the_request;
       

    function report_stock()
    {
        return view('manufacture.report.report_stock');
    }
    function report_order()
    {
        return view('manufacture.report.report_order');
    }
    function report_lab()
    {
        return view('manufacture.report.report_lab');
    }
    
    function dispatchByDateReport (Request $request){
        // dd($request);
        //validate inputs
        $request->validate([
            'dispatch_report_category' => 'required|in:all,jobcard,cash',
            'from_date' => 'required',
            'to_date' => 'required',
        ], [
            'dispatch_report_category.required'    => 'Please select the Category.',
            'dispatch_report_category.in'    => 'Please select the Category.',
            'from_date.required'    => 'The From Date is required.',
            'to_date.required'    => 'The To Date is required.',          
         ]);

        
        $this->the_request = $request;
        

        //Dispatches in Range
        if($request['dispatch_report_category'] == 'jobcard'){
            //filters - Contractors
            // dd($this->the_request);
            $the_report_dispatches = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
            ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
            ->select('dispatches.id as id', 'dispatches.dispatch_number as dispatch_number', 
            'dispatches.status as status', 'dispatches.weight_out_datetime as weight_out_datetime', 
            'dispatches.reference as reference', 'dispatches.registration_number as registration_number', 
            'dispatches.plant_id as plant_id', 'dispatches.delivery_zone as delivery_zone', 
            'dispatches.customer_id as customer_id', 'dispatches.job_id as job_id', 
            'dispatches.product_id as product_id', 'dispatches.qty as qty', 
            'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
            ->where('dispatches.status', 'Dispatched')
            ->where('dispatches.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('dispatches.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where (function($query){                
                if($this->the_request['job_number_filter'] != '0'){                    
                    $query->where('dispatches.job_id', $this->the_request['job_number_filter']);    
                }
            })
            ->where (function($query){
                if($this->the_request['site_number_filter'] != '0'){
                    $query->where('jobs.site_number', $this->the_request['site_number_filter']);                                  
                }
            })
            ->where (function($query){
                if($this->the_request['ref_number_filter'] != '0'){
                    $query->where('dispatches.reference', $this->the_request['ref_number_filter']);                    
                }
            })
            ->where('dispatches.job_id','!=', '0')
            ->groupBy('dispatches.job_id')
            ->orderBy('dispatches.dispatch_number', 'asc')
            ->get();
            /* $query = str_replace(array('?'), array('\'%s\''), $the_report_dispatches->toSql());
            $query = vsprintf($query, $the_report_dispatches->getBindings());
            dd($query);
            dd($the_report_dispatches); */
            $report_title = 'Transaction Report - Contractors - from '.$request['from_date'].' to '.$request['to_date'];
        } elseif($request['dispatch_report_category'] == 'cash'){
            //filters - Cash
            /* $the_report_dispatches = ManufactureJobcardProductDispatches::where('status', 'Dispatched')
            ->where('weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where('customer_id','!=', '0')
            ->groupBy('customer_id')
            ->orderBy('dispatch_number', 'asc')            
            ->get(); */
            $the_report_dispatches = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
            ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
            ->select('dispatches.id as id', 'dispatches.dispatch_number as dispatch_number', 
            'dispatches.status as status', 'dispatches.weight_out_datetime as weight_out_datetime', 
            'dispatches.reference as reference', 'dispatches.registration_number as registration_number', 
            'dispatches.plant_id as plant_id', 'dispatches.delivery_zone as delivery_zone', 
            'dispatches.customer_id as customer_id', 'dispatches.job_id as job_id', 
            'dispatches.product_id as product_id', 'dispatches.qty as qty', 
            'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
            ->where('dispatches.status', 'Dispatched')
            ->where('dispatches.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('dispatches.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where (function($query){                
                if($this->the_request['job_number_filter'] != '0'){                    
                    $query->where('dispatches.job_id', $this->the_request['job_number_filter']);    
                }
            })
            ->where (function($query){
                if($this->the_request['site_number_filter'] != '0'){
                    $query->where('jobs.site_number', $this->the_request['site_number_filter']);                                  
                }
            })
            ->where (function($query){
                if($this->the_request['ref_number_filter'] != '0'){
                    $query->where('dispatches.reference', $this->the_request['ref_number_filter']);                    
                }
            })
            ->where('dispatches.customer_id','!=', '0')
            ->groupBy('dispatches.customer_id')
            ->orderBy('dispatches.dispatch_number', 'asc')            
            ->get();

            $report_title = 'Transaction Report - Cash Clients - from '.$request['from_date'].' to '.$request['to_date'];
        } else {
            //filters - All - Will add Cash Clients first then add Jobs during loop below - GroupBy Limitiations on dual fields                        
            $the_report_dispatches = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
            ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
            ->select('dispatches.id as id', 'dispatches.dispatch_number as dispatch_number', 
            'dispatches.status as status', 'dispatches.weight_out_datetime as weight_out_datetime', 
            'dispatches.reference as reference', 'dispatches.registration_number as registration_number', 
            'dispatches.plant_id as plant_id', 'dispatches.delivery_zone as delivery_zone', 
            'dispatches.customer_id as customer_id', 'dispatches.job_id as job_id', 
            'dispatches.product_id as product_id', 'dispatches.qty as qty', 
            'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
            ->where('dispatches.status', 'Dispatched')
            ->where('dispatches.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('dispatches.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where (function($query){                
                if($this->the_request['job_number_filter'] != '0'){                    
                    $query->where('dispatches.job_id', $this->the_request['job_number_filter']);    
                }
            })
            ->where (function($query){
                if($this->the_request['site_number_filter'] != '0'){
                    $query->where('jobs.site_number', $this->the_request['site_number_filter']);                                  
                }
            })
            ->where (function($query){
                if($this->the_request['ref_number_filter'] != '0'){
                    $query->where('dispatches.reference', $this->the_request['ref_number_filter']);                    
                }
            })
            ->where('dispatches.customer_id','!=', '0')                        
            ->groupBy('dispatches.customer_id')
            ->orderBy('dispatches.dispatch_number', 'asc')
            ->get();
            $report_title = 'Transaction Report - All Dispatches - from '.$request['from_date'].' to '.$request['to_date'];
            
        }

        // dd($the_report_dispatches);

        $company_details = Settings::first()->toArray();                        
        
        //Dispatch Transactions in Range        
        /* $the_report_dispatch_transactions = ManufactureProductTransactions::whereIn('dispatch_id', $the_report_dispatch->pluck('id'))
        ->where('status', 'Dispatched')
        ->get(); */// if you want to create a complete collection of the collection Method 1
        /* foreach ($the_report_dispatch as $dispatch_entry) {
            $the_report_dispatch_transactions = $dispatch_entry->linked_transactions();
            dd($the_report_dispatch_transactions);    
        } */// If you want to call linked_transactions() for each item in collection Method 2*          
                
        //Clear Totals        
        $group_qty_sum = 0.000;
        $group_mass_sum = 0.000;
        $total_qty_sum = 0.000;
        $total_mass_sum = 0.000;

        $pdf='';           
        
        $pdf .= "<table style=\"border-collapse: collapse; table-layout: fixed; width: 840px;\">
                    <thead>
                        <tr>
                            <th style=\" font-weight: bold; font-size: 14px; text-align: center; padding: 10px;\" colspan='13'>*** ".strtoupper($company_details['trade_name'])." ***</th>                            
                        </tr>
                        <tr>
                            <th style=\" font-weight: bold; font-size: 11px; text-align: center; padding: 10px;\" colspan='13'>{$report_title}</th>
                            <br>
                        </tr> 
                        <tr style=\"background-color: rgb(85, 85, 85);\">
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Document No</div></th>
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Type</div></th>
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Status</div></th>
                            <th><div style=\"width: 97px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Date</div></th>
                            <th><div style=\"width: 80px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Ref No</div></th>
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Reg No</div></th>
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Del Zone</div></th>                            
                            <th><div style=\"width: 140px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Customer / Contractor Name</div></th>
                            <th><div style=\"width: 60px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Jobcard</div></th>                            
                            <th><div style=\"width: 40px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Product Code</div></th>
                            <th><div style=\"width: 200px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: left; padding: 3px;\">Product Name</div></th>
                            <th><div style=\"width: 40px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: right; padding: 3px;\">Qty</div></th>
                            <th><div style=\"width: 40px; overflow: scroll; font-weight: bold; font-size: 11px; color: #FFFFFF; text-align: right; padding: 3px;\">Net Mass</div></th>
                        </tr>
                    </thead>
                    <tbody>";

        

        foreach ($the_report_dispatches as $dispatch) {
            //Reset Group Totals
            // dd($dispatch);
            $group_qty_sum = 0.000;
            $group_mass_sum = 0.000;

            //Dispatch Header Item
            if($dispatch['product_id'] != '0'){
                $pdf .= "<tr>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">{$dispatch['dispatch_number']}</div></td>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll; font-size: 9px; text-align: left; padding: 3px;\">Dispatch</div></td>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">{$dispatch['status']}</div></td>
                        <td><div style=\"width: 97px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">{$dispatch['weight_out_datetime']}</div></td>
                        <td><div style=\"width: 80px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">{$dispatch['reference']}</div></td>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".(strlen($dispatch['registration_number']) == 0 && $dispatch['plant_id'] > 0 ? $dispatch->plant()->reg_number:$dispatch['registration_number'])."</div></td>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:'')."</div></td>
                        <td><div style=\"width: 140px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".($dispatch['customer_id'] == '0' ? ucfirst($dispatch->jobcard()->contractor):ucfirst($dispatch->customer()->name))."</div></td>
                        <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".($dispatch['job_id'] != '0' ? $dispatch->jobcard()->jobcard_number:'')."</div></td>
                        <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".($dispatch['product_id'] != '0' ? $dispatch->product()->code:'')."</div></td>
                        <td><div style=\"width: 200px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: left; padding: 3px;\">".($dispatch['product_id'] != '0' ? $dispatch->product()->description:'')."</div></td>
                        <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: right; padding: 3px;\">".($dispatch['product_id'] != '0' ? ($dispatch->product()->weighed_product == '0' ? $dispatch['qty']:''):'')."</div></td>
                        <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 9px; text-align: right; padding: 3px;\">".($dispatch['product_id'] != '0' ? ($dispatch->product()->weighed_product == '1' ? $dispatch['qty']:''):'')."</div></td>
                    </tr>";
            
                //Totaling Dispatch Header Item
                
                if($dispatch->product()->weighed_product == '0'){
                    
                    $group_qty_sum = $group_qty_sum + (float)$dispatch['qty'];
                    $total_qty_sum = $total_qty_sum + (float)$dispatch['qty'];
                    // dd('GT Qty:'.$total_qty_sum.', This Grp Qty:'.$group_qty_sum);                    
                }
                elseif ($dispatch->product()->weighed_product == '1'){
                    
                    $group_mass_sum = $group_mass_sum + (float)$dispatch['qty'];
                    $total_mass_sum = $total_mass_sum + (float)$dispatch['qty'];
                    // dd('GT Mass:'.$total_mass_sum.', This Grp Mass:'.$group_mass_sum);
                }                
                
            } 
            
            
             
            //Dispatch Transaction Items
            foreach ($dispatch->linked_transactions() as $dispatch_transactions) {
                // dd($dispatch_transactions);
                $pdf .= "<tr>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['dispatch_number']}</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">Dispatch</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions['status']}</div></td>
                            <td><div style=\"width: 97px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['weight_out_datetime']}</div></td>
                            <td><div style=\"width: 80px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['reference']}</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".(strlen($dispatch['registration_number']) == 0 && $dispatch['plant_id'] > 0 ? $dispatch->plant()->reg_number:$dispatch['registration_number'])."</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:'')."</div></td>
                            <td><div style=\"width: 140px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['customer_id'] == '0' ? ucfirst($dispatch->jobcard()->contractor):ucfirst($dispatch->customer()->name))."</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['job_id'] != '0' ? $dispatch->jobcard()->jobcard_number:'')."</div></td>                            
                            <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions->product()->code}</div></td>
                            <td><div style=\"width: 200px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions->product()->description}</div></td>
                            <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: right; padding: 1px;\">".($dispatch_transactions->product()->weighed_product == '0' ? \App\Http\Controllers\Functions::negate($dispatch_transactions['qty']):'')."</div></td>
                            <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: right; padding: 1px;\">".($dispatch_transactions->product()->weighed_product == '1' ? \App\Http\Controllers\Functions::negate($dispatch_transactions['qty']):'')."</div></td>                                                                                   
                        </tr>";

                        //Totaling Transaction Items                       
                        if ($dispatch_transactions->product()->weighed_product == '1'){
                            $group_mass_sum = $group_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                            $total_mass_sum = $total_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                        }
                        elseif ($dispatch_transactions->product()->weighed_product == '0'){
                            $group_qty_sum = $group_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                            $total_qty_sum = $total_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                        }                            
                        
            }
            //Totals Line           
            // if($dispatch['product_id'] != '0'||isset($dispatch_transactions)){
                $pdf .= "<tr>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>                            
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.0px double rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.0px double rgb(39, 39, 39); padding: 1px;\"></td>                                                                                   
                        </tr>
                        <tr>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                            
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\">Total</td>
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($group_qty_sum, 3)."</td>
                            <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($group_mass_sum, 3)."</td>                                                                                   
                        </tr>
                        ";
            // }
        }
        //<td style=\"font-weight: bold; overflow: scroll;  font-size: 9px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\">".($dispatch['customer_id'] == '0' ? ucfirst($dispatch->jobcard()->contractor):ucfirst($dispatch->customer()->name))."</td>//old line total client ref. removed due to wierd scaling issues on cells when text overflows

        if($request['dispatch_report_category'] == 'all'){
            //filters - All            
            /* $the_report_dispatches = ManufactureJobcardProductDispatches::where('status', 'Dispatched')
            ->where('weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where('job_id','!=', '0') 
            ->orderBy('dispatch_number', 'asc')
            ->groupBy('job_id')
            ->get(); */
            $the_report_dispatches = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
            ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
            ->select('dispatches.id as id', 'dispatches.dispatch_number as dispatch_number', 
            'dispatches.status as status', 'dispatches.weight_out_datetime as weight_out_datetime', 
            'dispatches.reference as reference', 'dispatches.registration_number as registration_number', 
            'dispatches.plant_id as plant_id', 'dispatches.delivery_zone as delivery_zone', 
            'dispatches.customer_id as customer_id', 'dispatches.job_id as job_id', 
            'dispatches.product_id as product_id', 'dispatches.qty as qty', 
            'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
            ->where('dispatches.status', 'Dispatched')
            ->where('dispatches.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
            ->where('dispatches.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
            ->where (function($query){                
                if($this->the_request['job_number_filter'] != '0'){                    
                    $query->where('dispatches.job_id', $this->the_request['job_number_filter']);    
                }
            })
            ->where (function($query){
                if($this->the_request['site_number_filter'] != '0'){
                    $query->where('jobs.site_number', $this->the_request['site_number_filter']);                                  
                }
            })
            ->where (function($query){
                if($this->the_request['ref_number_filter'] != '0'){
                    $query->where('dispatches.reference', $this->the_request['ref_number_filter']);                    
                }
            })
            ->where('dispatches.job_id','!=', '0')                        
            ->groupBy('dispatches.job_id')
            ->orderBy('dispatches.dispatch_number', 'asc')
            ->get();
            /* $query = str_replace(array('?'), array('\'%s\''), $the_report_dispatches->toSql());
            $query = vsprintf($query, $the_report_dispatches->getBindings());
            dd($query); */
                        

            foreach ($the_report_dispatches as $dispatch) {
                //Reset Group Totals
                $group_qty_sum = 0.000;
                $group_mass_sum = 0.000;

                //Dispatch Header Item
                if($dispatch['product_id'] != '0'){

                    $pdf .= "<tr>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['dispatch_number']}</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">Dispatch</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['status']}</div></td>
                            <td><div style=\"width: 97px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['weight_out_datetime']}</div></td>
                            <td><div style=\"width: 80px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['reference']}</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".(strlen($dispatch['registration_number']) == 0 && $dispatch['plant_id'] > 0 ? $dispatch->plant()->reg_number:$dispatch['registration_number'])."</div></td>
                            <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:'')."</div></td>
                            <td><div style=\"width: 140px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['customer_id'] == '0' ? ucfirst($dispatch->jobcard()->contractor):ucfirst($dispatch->customer()->name))."</div></td>
                            <td><div style=\"width: 60px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['job_id'] != '0' ? $dispatch->jobcard()->jobcard_number:'')."</div></td>
                            <td><div style=\"width: 40px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['product_id'] != '0' ? $dispatch->product()->code:'')."</div></td>
                            <td><div style=\"width: 200px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['product_id'] != '0' ? $dispatch->product()->description:'')."</div></td>
                            <td><div style=\"width: 40px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: right; padding: 1px;\">".($dispatch['product_id'] != '0' ? ($dispatch->product()->weighed_product == '0' ? $dispatch['qty']:''):'')."</div></td>
                            <td><div style=\"width: 40px; font-weight: normal;  overflow: scroll; font-size: 10px; text-align: right; padding: 1px;\">".($dispatch['product_id'] != '0' ? ($dispatch->product()->weighed_product == '1' ? $dispatch['qty']:''):'')."</div></td>
                        </tr>";

                        //Totaling Dispatch Header Item
                        
                            if($dispatch->product()->weighed_product == '0'){
                    
                                $group_qty_sum = $group_qty_sum + (float)$dispatch['qty'];
                                $total_qty_sum = $total_qty_sum + (float)$dispatch['qty'];
                                // dd('GT Qty:'.$total_qty_sum.', This Grp Qty:'.$group_qty_sum);                    
                            }
                            elseif ($dispatch->product()->weighed_product == '1'){
                                
                                $group_mass_sum = $group_mass_sum + (float)$dispatch['qty'];
                                $total_mass_sum = $total_mass_sum + (float)$dispatch['qty'];
                                // dd('GT Mass:'.$total_mass_sum.', This Grp Mass:'.$group_mass_sum);
                            }
                }

                //Dispatch Transaction Items
                foreach ($dispatch->linked_transactions() as $dispatch_transactions) {
                    $pdf .= "<tr>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['dispatch_number']}</div></td>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">Dispatch</div></td>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions['status']}</div></td>
                                <td><div style=\"width: 97px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['weight_out_datetime']}</div></td>
                                <td><div style=\"width: 80px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch['reference']}</div></td>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".(strlen($dispatch['registration_number']) == 0 && $dispatch['plant_id'] > 0 ? $dispatch->plant()->reg_number:$dispatch['registration_number'])."</div></td>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:'')."</div></td>
                                <td><div style=\"width: 140px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['customer_id'] == '0' ? ucfirst($dispatch->jobcard()->contractor):ucfirst($dispatch->customer()->name))."</div></td>
                                <td><div style=\"width: 60px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">".($dispatch['job_id'] != '0' ? $dispatch->jobcard()->jobcard_number:'')."</div></td>                            
                                <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions->product()->code}</div></td>
                                <td><div style=\"width: 200px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\">{$dispatch_transactions->product()->description}</div></td>
                                <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: right; padding: 1px;\">".($dispatch_transactions->product()->weighed_product == '0' ? \App\Http\Controllers\Functions::negate($dispatch_transactions['qty']):'')."</div></td>
                                <td><div style=\"width: 40px; font-weight: normal; overflow: scroll;  font-size: 10px; text-align: right; padding: 1px;\">".($dispatch_transactions->product()->weighed_product == '1' ? \App\Http\Controllers\Functions::negate($dispatch_transactions['qty']):'')."</div></td>                                                                                   
                            </tr>";

                            //Totaling Transaction Items                            
                            if ($dispatch_transactions->product()->weighed_product == '1'){
                                $group_mass_sum = $group_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                                $total_mass_sum = $total_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                            }
                            elseif ($dispatch_transactions->product()->weighed_product == '0'){
                                $group_qty_sum = $group_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                                $total_qty_sum = $total_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch_transactions['qty']);
                            }                            
                            
                }
                //Totals Line
                // if($dispatch['product_id'] != '0'||isset($dispatch_transactions)){              
                    $pdf .= "<tr>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>                            
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; padding: 1px;\"></td>
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.0px double rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.0px double rgb(39, 39, 39); padding: 1px;\"></td>                                                                                   
                            </tr>
                            <tr>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                            
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\">Total</td>
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($group_qty_sum, 3)."</td>
                                <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($group_mass_sum, 3)."</td>                                                                                   
                            </tr>
                            ";
                // }
            }

        }

        //Grand Totals Line
        if(count($the_report_dispatches)>0){
            // dd(count($the_report_dispatches));
            $pdf .= "
                    <tr>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                            
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                                                                                   
                    </tr>
                    <tr>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal;  overflow: scroll; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                            
                        <td style=\"font-weight: normal; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\">Grand Totals</td>
                        <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($total_qty_sum, 3)."</td>
                        <td style=\"font-weight: bold; overflow: scroll;  font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); border-top: 1.5px single rgb(39, 39, 39); padding: 1px;\">".number_format($total_mass_sum, 3)."</td>                                                                                   
                    </tr>
                    ";
        } else {
            // dd(count($the_report_dispatches));
            $pdf .= "
                    <tr>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\">Nothing to list matching the provided parameters...</td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                            
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: normal; font-size: 10px; text-align: left; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: bold; font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>
                        <td style=\"font-weight: bold; font-size: 10px; text-align: right; border-bottom: 1.5px single rgb(39, 39, 39); padding: 1px;\"></td>                                                                                   
                    </tr>";
        }
        

        $pdf .= "</tbody>
                                  
                </table>
                <br>
                <table style='width: 1080px;'>
                    <tfoot>
                    <tr>
                        <td style='width: 100%; text-align: right; font-weight: bold; font-size: 9px;'>Report generated @".date("Y-m-d h:i:s",time())."</td>
                    </tr>
                    </tfoot>
                </table>";                            
                        
            Functions::printPDF($pdf,'Transaction Report-' . $request['dispatch_report_category'] . ' from ' . $request['from_date'] . ' to ' . $request['to_date'], true, false, 'L', 'A4');    
            
            
    }

    function report_dispatch()
    {
        
        return view('manufacture.report.report_dispatch');
    }
}
