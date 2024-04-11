<?php

namespace App\Http\Controllers\Manufacture\Report;


use App\Models\Settings;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DispatchReportExport;

use function PHPUnit\Framework\isNull;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\ManufactureProductTransactions;
use App\Models\ManufactureJobcardProductDispatches;

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
            
        $the_report_dispatches = ManufactureProductTransactions::from('manufacture_product_transactions as transactions')
        ->join('manufacture_jobcard_product_dispatches as dispatches', 'dispatches.id', '=', 'transactions.dispatch_id', 'left outer')
        ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
        ->join('manufacture_customers as customers', 'customers.id', '=', 'dispatches.customer_id', 'left outer')            
        ->join('plants_tbl as plant', 'plant.plant_id', '=', 'dispatches.plant_id', 'left outer')              
        ->join('manufacture_products as products', 'products.id', '=', 'transactions.product_id', 'left outer')
        ->select('dispatches.id as id', 'dispatches.dispatch_number as dispatch_number', 
        'dispatches.status as status', 'dispatches.weight_out_datetime as weight_out_datetime', 
        'dispatches.reference as reference', 'dispatches.registration_number as registration_number', 
        'dispatches.plant_id as plant_id', 'dispatches.delivery_zone as delivery_zone', 
        'dispatches.customer_id as customer_id', 'dispatches.job_id as job_id', 
        'dispatches.product_id as product_id',  
        'dispatches.outsourced_contractor as outsourced_contractor',
        'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number','jobs.contractor as contractor_name',
        'customers.name as customer_name','customers.account_number as account_number',
        'transactions.product_id as transactions_product_id','transactions.status as transactions_status','transactions.qty as qty','plant.reg_number as plant_registration_number',
        'products.code as product_code', 'products.description as product_description', 'products.weighed_product as weighed_product')
        ->where('dispatches.status', 'Dispatched')
        ->where('dispatches.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
        ->where('dispatches.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')
        ->where (function($query){                
            if($this->the_request['dispatch_report_category'] == 'jobcard'){                    
                $query->where('dispatches.job_id','!=', '0');                        
            }
        })
        ->where (function($query){                
            if($this->the_request['dispatch_report_category'] == 'cash'){                    
                $query->where('dispatches.customer_id','!=', '0');                        
            }
        })
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
        ->where (function($query){                
            if($this->the_request['customer_name_filter'] != '0'){                    
                $query->where('dispatches.customer_id', $this->the_request['customer_name_filter']);    
            }
        })
        ->where (function($query){
            if($this->the_request['account_number_filter'] != '0'){
                $query->where('customers.account_number', $this->the_request['account_number_filter']);                                  
            }
        })
        ->where (function($query){
            if($this->the_request['product_description_filter'] != '0'){
                $query->where('transactions.product_id', $this->the_request['product_description_filter']);                    
            }
        });
        //Order By Clause + Get
        if($this->the_request['dispatch_report_group_by'] == 'dispatch'){
            $this->the_request['dispatch_report_group_by']='id';
            $the_report_dispatches = $the_report_dispatches->orderBy('dispatches.id', 'asc')->get();                                
        } elseif($this->the_request['dispatch_report_group_by'] == 'jobcard'){
            $this->the_request['dispatch_report_group_by']='job_id';
            $the_report_dispatches = $the_report_dispatches->orderBy('dispatches.job_id', 'asc')->get();                                
        } elseif($this->the_request['dispatch_report_group_by'] == 'reference'){
            $this->the_request['dispatch_report_group_by']='reference';
            $the_report_dispatches = $the_report_dispatches->orderBy('dispatches.reference', 'asc')->get();
        } elseif($this->the_request['dispatch_report_group_by'] == 'site'){
            $this->the_request['dispatch_report_group_by']='site_number';      
            $the_report_dispatches = $the_report_dispatches->orderBy('jobs.site_number', 'asc')->get();                          
        } elseif($this->the_request['dispatch_report_group_by'] == 'customer'){
            $this->the_request['dispatch_report_group_by']='customer_id';          
            $the_report_dispatches = $the_report_dispatches->orderBy('dispatches.customer_id', 'asc')->get();                      
        } elseif($this->the_request['dispatch_report_group_by'] == 'product'){
            $this->the_request['dispatch_report_group_by']='product_code';                   
            $the_report_dispatches = $the_report_dispatches->orderBy('transactions.product_id', 'asc')->get();             
        } elseif($this->the_request['dispatch_report_group_by'] == 'none'){
            $this->the_request['dispatch_report_group_by']='';                   
            $the_report_dispatches = $the_report_dispatches->orderBy('dispatches.id', 'asc')->get();             
        }      
        
        
        /* $query = str_replace(array('?'), array('\'%s\''), $the_report_dispatches->toSql());
        $query = vsprintf($query, $the_report_dispatches->getBindings());
        dd($query);
        dd($the_report_dispatches); */
        $report_title = 'Transaction Report - '.ucfirst($this->the_request['dispatch_report_category']).' Clients - from '.$request['from_date'].' to '.$request['to_date'];
        
        $company_details = Settings::first()->toArray();                        
                
        //Clear Totals        
        $group_qty_sum = 0.000;
        $group_mass_sum = 0.000;
        $total_qty_sum = 0.000;
        $total_mass_sum = 0.000;

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
                
        //Heading Rows
        $activeWorksheet->setCellValue('A1', '*** '.strtoupper($company_details['trade_name']).' ***');
        $activeWorksheet->mergeCells('A1:M1');
        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 16,                
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ], 
                'left' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ], 
                'right' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ],                             
            ]            
        ];
        $activeWorksheet->getStyle('A1:M1')->applyFromArray($styleArray);

        $activeWorksheet->setCellValue('A2', $report_title);        
        $activeWorksheet->mergeCells('A2:M2');
        $styleArray['font']['size']=12;
        $styleArray['font']['italic']=true;
        $styleArray['font']['bold']=false;
        $styleArray['borders']['top']=[''];
        $styleArray['borders']['bottom']['borderStyle']=Border::BORDER_MEDIUM;
        $styleArray['borders']['bottom']['color']['argb']='#000000';
        $activeWorksheet->getStyle('A2:M2')->applyFromArray($styleArray);
        
        //Column Headers
        $header_row_array = ['Document No', 'Type', 'Status', 'Date', 'Reference No', 'Registration No', 'Delivery Zone', 'Customer / Contractor Name', 'Jobcard', 'Product Code', 'Product Name', 'Qty', 'Net Mass'];
        $activeWorksheet->fromArray( $header_row_array, NULL, 'A3' );
        $styleArray['font']['size']=11;
        $styleArray['font']['italic']=false;
        $styleArray['font']['bold']=true;
        $styleArray['alignment']['horizontal']=Alignment::HORIZONTAL_LEFT;        
        $activeWorksheet->getStyle('A3:M3')->applyFromArray($styleArray);                
        
        //Setting Column Widths
        $activeWorksheet->getColumnDimension('A')->setWidth(10);
        $activeWorksheet->getColumnDimension('B')->setWidth(10);
        $activeWorksheet->getColumnDimension('C')->setWidth(11);
        $activeWorksheet->getColumnDimension('D')->setWidth(18);
        $activeWorksheet->getColumnDimension('E')->setWidth(16);
        $activeWorksheet->getColumnDimension('F')->setWidth(15);
        $activeWorksheet->getColumnDimension('G')->setWidth(13);
        $activeWorksheet->getColumnDimension('H')->setWidth(41);
        $activeWorksheet->getColumnDimension('I')->setWidth(10);
        $activeWorksheet->getColumnDimension('J')->setWidth(13);
        $activeWorksheet->getColumnDimension('K')->setWidth(41);
        $activeWorksheet->getStyle('L:M')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $activeWorksheet->getColumnDimension('L')->setWidth(10);
        $activeWorksheet->getColumnDimension('M')->setWidth(10);
       
        $previous_group_id = '-1';

        foreach ($the_report_dispatches as $dispatch) {
            $row = $activeWorksheet->getHighestRow()+1;

            if($previous_group_id != '-1' && ($dispatch[$this->the_request['dispatch_report_group_by']]  != $previous_group_id && $this->the_request['dispatch_report_group_by'] != 'none')) {                
                //New Dispatch Group
                //Insert Group Total Line from Previous Group                
                $activeWorksheet->setCellValue('L'.$row, number_format($group_qty_sum, 3));
                $activeWorksheet->setCellValue('M'.$row, number_format($group_mass_sum, 3));
                $styleArray = [];
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],  
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => '#000000'],
                        ],                                              
                    ],
                    'font' => [
                        'bold' => true,                                        
                    ],
                ];      
                      
                $activeWorksheet->getStyle('A'.$row.':M'.$row)->applyFromArray($styleArray);
                $styleArray['borders']['left']=[''];
                $styleArray['borders']['top']['borderStyle']=Border::BORDER_MEDIUM;
                $styleArray['borders']['top']['color']['argb']='#000000';
                $activeWorksheet->getStyle('L'.$row.':M'.$row)->applyFromArray($styleArray);                
                
                //Reset Group Totals            
                $group_qty_sum = 0.000;
                $group_mass_sum = 0.000;

                $row = $activeWorksheet->getHighestRow()+1;                

            } 
                //Continuing a Dispatch Group / First Row 
                //Totaling Transaction Items
                if ($dispatch['weighed_product'] == '1'){
                    $group_mass_sum = $group_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch['qty']);
                    $total_mass_sum = $total_mass_sum + (float)\App\Http\Controllers\Functions::negate($dispatch['qty']);
                }                        
                elseif ($dispatch['weighed_product'] == '0'){
                    $group_qty_sum = $group_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch['qty']);
                    $total_qty_sum = $total_qty_sum + (float)\App\Http\Controllers\Functions::negate($dispatch['qty']);
                }                        
                
                $insert_array = [
                    $dispatch['dispatch_number'],
                    'Dispatch',
                    $dispatch['status'],
                    $dispatch['weight_out_datetime'],
                    $dispatch['reference'],
                    (strlen($dispatch['registration_number']) == 0 && $dispatch['plant_id'] > 0 ? $dispatch['plant_registration_number'] : (strlen($dispatch['outsourced_contractor']) != 0 ?  $dispatch['registration_number']."*" : $dispatch['registration_number'])),
                    ($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:''),
                    ($dispatch['customer_id'] == '0' ? ucfirst($dispatch['contractor_name']):ucfirst($dispatch['customer_name'])),
                    ($dispatch['job_id'] != '0' ? $dispatch['jobcard_number']:''),
                    $dispatch['product_code'],
                    $dispatch['product_description'],
                    ($dispatch['weighed_product'] == '0' ? \App\Http\Controllers\Functions::negate($dispatch['qty']):''),
                    ($dispatch['weighed_product'] == '1' ? \App\Http\Controllers\Functions::negate($dispatch['qty']):'')
                ];                
                
                $activeWorksheet->fromArray( $insert_array, NULL, 'A'.$row );
                $styleArray = [];
                $styleArray = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                    'font' => [
                        'bold' => false,                                     
                    ],
                    'alignment' => [
                        'wraptext' => true,               
                    ],
                ];                
                $activeWorksheet->getStyle('A'.$row.':M'.$row)->applyFromArray($styleArray);

                $previous_group_id = $dispatch[$this->the_request['dispatch_report_group_by']];
           

        }
        //Generate Last Dispatch Group Total Line        
        //Insert Group Total Line from Previous Group
        $row = $activeWorksheet->getHighestRow()+1;                
        $activeWorksheet->setCellValue('L'.$row, number_format($group_qty_sum, 3));
        $activeWorksheet->setCellValue('M'.$row, number_format($group_mass_sum, 3));
        $styleArray = [];
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '#000000'],
                ],  
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '#000000'],
                ],                      
                'top' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '#000000'],
                ],
            ],
            'font' => [
                'bold' => true,                
            ],
        ];                
        $activeWorksheet->getStyle('A'.$row.':M'.$row)->applyFromArray($styleArray);                
        
        //Reset Group Totals            
        $group_qty_sum = 0.000;
        $group_mass_sum = 0.000;

        $row = $activeWorksheet->getHighestRow()+1;        

        //Grand Totals Line        
        if(count($the_report_dispatches)>0){
            $last_row = $activeWorksheet->getHighestRow()+2;
            $activeWorksheet->insertNewRowBefore($last_row); 
            $activeWorksheet->setCellValue('K'.$last_row, 'Grand Totals');
            $activeWorksheet->setCellValue('L'.$last_row, number_format($total_qty_sum, 3));
            $activeWorksheet->setCellValue('M'.$last_row, number_format($total_mass_sum, 3));
            $styleArray = [];
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['argb' => '#000000'],
                    ],
                ],
            ];                
            $activeWorksheet->getStyle('K'.$last_row.':M'.$last_row)->applyFromArray($styleArray);
            $activeWorksheet->getStyle('K'.$last_row.':'.'M'.$last_row)->getFont()->setBold( true );                    
        } else {
            $last_row = $activeWorksheet->getHighestRow()+1;
            $activeWorksheet->insertNewRowBefore($last_row);
            $activeWorksheet->setCellValue('A'.$last_row, 'Nothing to list matching the provided parameters...');                 
        }      

        //Footer Row
        $footer_row = $activeWorksheet->getHighestRow()+2;
        $activeWorksheet->insertNewRowBefore($footer_row);
        $activeWorksheet->getStyle('A'.$footer_row.':M'.$footer_row)->getFont()->setSize(8);
        $activeWorksheet->setCellValue('A'.$footer_row, '* Outsourced Contractor Used');        
        $activeWorksheet->setCellValue('M'.$footer_row, 'Report generated @'.date('Y-m-d h:i:s',time()));        
                
        $filename = 'Transaction Report-' . ucfirst($request['dispatch_report_category']) . ' Clients from ' . $request['from_date'] . ' to ' . $request['to_date'].' generated '.date('Ymd his',time()).'.xlsx';
        
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        exit($xlsxWriter->save('php://output'));
    }    

    function report_dispatch()
    {
        
        return view('manufacture.report.report_dispatch');
    }
}


