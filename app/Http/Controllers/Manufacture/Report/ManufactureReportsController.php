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
use App\Models\ManufactureProducts;

class ManufactureReportsController extends Controller
{

    protected $the_request;
       

    function report_stock()
    {
        return view('manufacture.report.report_stock');
    }

    function stockByDateReport (Request $request){
        
        //validate inputs
        $request->validate([
            'stock_report_category' => 'required|in:all,raw,manufactured',
            'stock_report_format' => 'required|in:flattened,transactional',
            'from_date' => 'required',
            'to_date' => 'required',
        ], [
            'stock_report_category.required'    => 'Please select the Category.',
            'stock_report_category.in'    => 'Please select the Category.',
            'stock_report_format.required'    => 'Please select the Report Format.',
            'stock_report_format.in'    => 'Please select the Report Format.',
            'from_date.required'    => 'The From Date is required.',
            'to_date.required'    => 'The To Date is required.',          
         ]);

        
        $this->the_request = $request;

        //Generate Order By Fields
        if($this->the_request['stock_report_group_by'] == 'reference'){
            $this->the_request['stock_report_group_by']='reference';            
        } elseif($this->the_request['stock_report_group_by'] == 'supplier'){
            $this->the_request['stock_report_group_by']='type_id';                                            
        } elseif($this->the_request['stock_report_group_by'] == 'product'){
            $this->the_request['stock_report_group_by']='code';                               
        } elseif($this->the_request['stock_report_group_by'] == 'none'){
            $this->the_request['stock_report_group_by']='';                               
        }

        //Stock Transactions in Range             
        $the_report_transactions = ManufactureProductTransactions::
        join('manufacture_suppliers', 'manufacture_suppliers.id', '=', 'manufacture_product_transactions.type_id', 'left outer')
        ->join('manufacture_jobcard_product_dispatches as dispatches', 'dispatches.id', '=', 'manufacture_product_transactions.dispatch_id', 'left outer')
        ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')
        ->join('manufacture_customers as customers', 'customers.id', '=', 'dispatches.customer_id', 'left outer')
        ->join('manufacture_products', 'manufacture_products.id', '=', 'manufacture_product_transactions.product_id', 'left outer')                
        ->join('plants_tbl as plant', 'plant.plant_id', '=', 'dispatches.plant_id', 'left outer') 
        ->select('manufacture_product_transactions.id as id','manufacture_product_transactions.type as type','manufacture_product_transactions.type_id as type_id','manufacture_product_transactions.reference_number','manufacture_product_transactions.dispatch_id as dispatch_id'
        ,'manufacture_product_transactions.registration_number as registration_number','manufacture_product_transactions.qty as qty','manufacture_product_transactions.product_id as transactions_product_id','manufacture_product_transactions.weight_out_datetime as weight_out_datetime'
        ,'manufacture_product_transactions.comment as comment'
        ,'manufacture_suppliers.name as supplier_name'
        ,'customers.name as customer_name'
        ,'jobs.jobcard_number as jobcard_number','jobs.site_number as site_number'
        ,'dispatches.dispatch_number as dispatch_number','dispatches.plant_id as plant_id'
        ,'manufacture_products.code as code','manufacture_products.description as description','manufacture_products.has_recipe as has_recipe','manufacture_products.weighed_product as weighed_product'
        ,'plant.plant_number as plant_number')
        //All Status except Loading        
        ->where('manufacture_product_transactions.status', '<>', 'Loading')        
        ->where('manufacture_product_transactions.weight_out_datetime', '>=', $request['from_date'].' 00:00:01')
        ->where('manufacture_product_transactions.weight_out_datetime', '<=', $request['to_date'].' 23:59:59')               
        
        ->where (function($query){                
            if($this->the_request['stock_report_category'] == 'raw'){                    
                $query->where('manufacture_products.has_recipe', '0');                        
            }
        })
        ->where (function($query){                
            if($this->the_request['stock_report_category'] == 'manufactured'){                    
                $query->where('manufacture_products.has_recipe', '1');                        
            }
        })        
        ->where (function($query){
            if($this->the_request['ref_number_filter'] != '0'){
                $query->where('manufacture_product_transactions.reference_number', $this->the_request['ref_number_filter']);                    
            }
        })
        ->where (function($query){                
            if($this->the_request['supplier_name_filter'] != '0'){                    
                $query->where('manufacture_product_transactions.type_id', $this->the_request['supplier_name_filter'])
                ->where('manufacture_product_transactions.type','RET')
                ->orWhere('manufacture_product_transactions.type','REC');    
            }
        })        
        ->where (function($query){
            if($this->the_request['product_description_filter'] != '0'){
                $query->where('manufacture_product_transactions.product_id', $this->the_request['product_description_filter']);                    
            }
        })
        ->when (function($query){
            if($this->the_request['stock_report_group_by']=='reference'){
                $query->orderBy('manufacture_product_transactions.reference_number', 'asc');                    
            } elseif ($this->the_request['stock_report_group_by']=='type_id'){
                $query->orderBy('manufacture_product_transactions.type_id', 'asc');
            } elseif ($this->the_request['stock_report_group_by']=='code'){
                $query->orderBy('manufacture_product_transactions.product_id', 'asc');
            } elseif ($this->the_request['stock_report_group_by']=''){
                $query->orderBy('manufacture_product_transactions.id', 'asc');
            }
        })           
        ->get();
                       
        //Generate Query as Template for Temp Table
        $query = str_replace(array('?'), array('\'%s\''), $the_report_transactions->toSql());
        $query = vsprintf($query, $the_report_transactions->getBindings());
        

        //Create Temp Table for Stock Transactions (Opening Stock, Stock Rec, Stock Cons, Stock Sold(Dispatch), Stock Ret, Adjustment,  On Hand)
        DB::unprepared( DB::raw( 'DROP TEMPORARY TABLE IF EXISTS stock_temp' ) );
        DB::statement( 'CREATE TEMPORARY TABLE stock_temp ' . $query );        
        // DB::unprepared( DB::raw( 'DROP TABLE IF EXISTS stock_temp' ) );
        // DB::statement( 'CREATE TABLE stock_temp ' . $query ); 
        $max_id = $the_report_transactions->max('manufacture_product_transactions.id');
        $max_id++;        
        unset($the_report_transactions);
        unset($query);        

        //Insert Stock Transactions (Opening Stock, Stock Rec, Stock Cons, Stock Sold(Dispatch), Stock Ret, Adjustment,  On Hand)
        $products=ManufactureProducts::whereActive('1')->get();        
                
        foreach($products as $product){

            if(($this->the_request['stock_report_category']=='raw' && $product->has_recipe == '0')||($this->the_request['stock_report_category']=='manufactured' && $product->has_recipe == '1')||($this->the_request['stock_report_category']=='all')){
                DB::table('stock_temp')->insert(['id'=>$max_id++, 'type'=>'OS', 'type_id'=>null, 'reference_number'=>null, 'dispatch_id'=>0, 'registration_number'=>null,
                    'qty'=>$product->getQtyByDate($request['from_date']), 'transactions_product_id'=>$product->id, 'weight_out_datetime'=>$request['from_date'].' 00:00:01',
                    'supplier_name'=>null, 'customer_name'=>null, 'jobcard_number'=>null, 'site_number'=>null, 'dispatch_number'=>null, 'registration_number'=>null, 
                    'plant_id'=>null, 'code'=>$product->code, 'description'=>$product->description, 'has_recipe'=>$product->has_recipe, 'weighed_product'=>$product->weighed_product,
                    'plant_number'=>null
                ]);

            }
            
        }

        if($request['stock_report_format']=='flattened'){
            //Summarised            

            $stock_temp = DB::select("select a.code, a.description, sum(ifnull(c.rec,0.000)) as rec, sum(ifnull(d.ret,0.000)) as ret, sum(ifnull(e.bat,0.000)) as bat, sum(ifnull(f.jdisp,0.000)) as jdisp, sum(ifnull(i.cdisp,0.000)) as cdisp, sum(ifnull(j.os,0.000)) as os, sum(ifnull(k.adj,0.000)) as adj,
                (sum(ifnull(c.rec,0.000)) + sum(ifnull(d.ret,0.000)) + sum(ifnull(e.bat,0.000)) + sum(ifnull(f.jdisp,0.000)) + sum(ifnull(i.cdisp,0.000)) + sum(ifnull(j.os,0.000)) + sum(ifnull(k.adj,0.000))) as oh
                from stock_temp as a
                 
                left join (select id, code, sum(qty) as rec from stock_temp where type='rec' group by code ) c on a.id = c.id
                left join (select id, code, sum(qty) as ret from stock_temp where type='ret' group by code ) d on a.id = d.id
                left join (select id, code, sum(qty) as bat from stock_temp where type='bat' group by code ) e on a.id = e.id
                left join (select id, code, sum(qty) as jdisp from stock_temp where type='jdisp' group by code ) f on a.id = f.id
                left join (select id, code, sum(qty) as cdisp from stock_temp where type='cdisp' group by code ) i on a.id = i.id
                left join (select id, code, sum(qty) as os from stock_temp where (type='os' or type='ob') group by code ) j on a.id = j.id
                left join (select id, code, sum(qty) as adj from stock_temp where type='adj' group by code ) k on a.id = k.id
                group by code
                order by code
            ");
            $stock_temp = collect ( $stock_temp );
            // sum(ifnull(b.ob,0.000)) as ob,
            // sum(ifnull(b.ob,0.000)) +  
            // left join (select id, code, sum(qty) as ob from stock_temp where type='ob' group by code ) b on a.id = b.id

        } else {
            //Transactional
            $stock_temp = DB::table('stock_temp')->select(DB::raw('weight_out_datetime as date, type, code, description, qty, comment, supplier_name as supplier, customer_name as customer, jobcard_number as jobcard, site_number as site,
            dispatch_number as dispatch, reference_number as reference, 0.000 as oh'))
            ->when (function($query){
                if($this->the_request['stock_report_group_by']=='type_id'){
                    $query->orderBy('supplier', 'asc');
                } elseif ($this->the_request['stock_report_group_by']=='reference'){
                    $query->orderBy('reference', 'asc');
                } elseif ($this->the_request['stock_report_group_by']=='code'){
                    $query->orderBy('code', 'asc');
                } 
            })
            ->orderBy('code','asc')->orderBy('weight_out_datetime','asc')
            ->get();
        }

        //Prepare Group Fields on New Stock_Temp Columns
        if($this->the_request['stock_report_group_by'] == 'reference'){
            $this->the_request['stock_report_group_by']='reference';            
        } elseif($this->the_request['stock_report_group_by'] == 'type_id'){
            $this->the_request['stock_report_group_by']='supplier';                                            
        } elseif($this->the_request['stock_report_group_by'] == ''){
            $this->the_request['stock_report_group_by']='';                                            
        } elseif($this->the_request['stock_report_group_by'] == 'code'){
            $this->the_request['stock_report_group_by']='code';                                            
        }

        $report_title = ($request['stock_report_format']=='flattened' ? 'Summarised Report - ' : 'Transactional Report - ') .ucfirst($this->the_request['stock_report_category']).' Stock - from '.$request['from_date'].' to '.$request['to_date']
        .($this->the_request['stock_report_group_by'] == '' ? '':
        ($this->the_request['stock_report_group_by'] == 'reference' ? ' - Grouped by Reference':
        ($this->the_request['stock_report_group_by'] == 'supplier' ? ' - Grouped by Supplier':
        ($this->the_request['stock_report_group_by'] == 'code' ? ' - Grouped by Product':''))));
        
        $company_details = Settings::first()->toArray();                        
                
        //Clear Totals        
        $group_qty_sum = 0.000;      

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
                
        //Heading Rows
        $activeWorksheet->setCellValue('A1', '*** '.strtoupper($company_details['trade_name']).' ***');
        $activeWorksheet->mergeCells('A1:'.($request['stock_report_format']=='flattened' ? 'J1':($this->the_request['stock_report_group_by'] == 'code' ? 'M1':'L1')));
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
        $activeWorksheet->getStyle('A1:'.($request['stock_report_format']=='flattened' ? 'J1':($this->the_request['stock_report_group_by'] == 'code' ? 'M1':'L1')))->applyFromArray($styleArray);

        $activeWorksheet->setCellValue('A2', $report_title);        
        $activeWorksheet->mergeCells('A2:'.($request['stock_report_format']=='flattened' ? 'J2':($this->the_request['stock_report_group_by'] == 'code' ? 'M2':'L2')));
        $styleArray['font']['size']=12;
        $styleArray['font']['italic']=true;
        $styleArray['font']['bold']=false;
        $styleArray['borders']['top']=[''];
        $styleArray['borders']['bottom']['borderStyle']=Border::BORDER_MEDIUM;
        $styleArray['borders']['bottom']['color']['argb']='#000000';
        $activeWorksheet->getStyle('A2:'.($request['stock_report_format']=='flattened' ? 'J2':($this->the_request['stock_report_group_by'] == 'code' ? 'M2':'L2')))->applyFromArray($styleArray);
        
        //Column Headers
        if($request['stock_report_format']=='flattened'){
            //Summarised
            $header_row_array = ['Product Code', 'Product Name', 'Opening Stock', 'Received', 'Returned', 'Batch Cons.', 'Jobcard Disp.', 'Customer Disp.', 'Adjustments', 'On Hand'];
        } else {
            //Transactional
            $header_row_array = ['Trans. Date', 'Transaction', 'Product Code', 'Product Name', 'Qty', 'Comment', 'Supplier', 'Customer', 'Jobcard', 'Site', 'Dispatch No'];
            if($this->the_request['stock_report_group_by'] == 'code'){
                array_push($header_row_array, 'Reference');
                array_push($header_row_array, 'Running Total');
            } else {
                array_push($header_row_array, 'Reference');
            }
        }
        
        $activeWorksheet->fromArray( $header_row_array, NULL, 'A3' );
        $styleArray['font']['size']=11;
        $styleArray['font']['italic']=false;
        $styleArray['font']['bold']=true;
        $styleArray['alignment']['horizontal']=Alignment::HORIZONTAL_LEFT;        
        $activeWorksheet->getStyle('A3:'.($request['stock_report_format']=='flattened' ? 'J3':($this->the_request['stock_report_group_by'] == 'code' ? 'M3':'L3')))->applyFromArray($styleArray);                
        
        //Setting Column Widths
        $activeWorksheet->getColumnDimension('A')->setWidth(($request['stock_report_format']=='flattened' ? 15:20));//code          date
        $activeWorksheet->getColumnDimension('B')->setWidth(($request['stock_report_format']=='flattened' ? 50:12));//description   type
        $activeWorksheet->getColumnDimension('C')->setWidth(15);//os            code        
        $activeWorksheet->getColumnDimension('D')->setWidth(($request['stock_report_format']=='flattened' ? 15:50));//rec           description        
        $activeWorksheet->getColumnDimension('E')->setWidth(15);//ret           qty
        if($request['stock_report_format']!='flattened'){
            //Transactional
            $activeWorksheet->getStyle('E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }        
        $activeWorksheet->getColumnDimension('F')->setWidth(($request['stock_report_format']=='flattened' ? 15:40));//bat           comment
        if($request['stock_report_format']!='flattened'){
            //Transactional
            $activeWorksheet->getStyle('F')->getAlignment()->setWrapText('true');
        }        
        $activeWorksheet->getColumnDimension('G')->setWidth(($request['stock_report_format']=='flattened' ? 15:30));//jdisp         supplier
        if($request['stock_report_format']!='flattened'){
            //Transactional
            $activeWorksheet->getStyle('G')->getAlignment()->setWrapText('true');
        }        
        $activeWorksheet->getColumnDimension('H')->setWidth(($request['stock_report_format']=='flattened' ? 15:30));//cdisp         customer
        if($request['stock_report_format']!='flattened'){
            //Transactional
            $activeWorksheet->getStyle('H')->getAlignment()->setWrapText('true');
        }        
        $activeWorksheet->getColumnDimension('I')->setWidth(($request['stock_report_format']=='flattened' ? 15:10));//adj           jobcard        
        $activeWorksheet->getColumnDimension('J')->setWidth(($request['stock_report_format']=='flattened' ? 15:10));//oh            site
        if($request['stock_report_format']=='flattened'){
            //Summarised
            $activeWorksheet->getStyle('C:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }
        if($request['stock_report_format']!='flattened'){
            //Transactional
            $activeWorksheet->getColumnDimension('K')->setWidth(12);//              dispatch
            $activeWorksheet->getColumnDimension('L')->setWidth(15);//              reference
            $activeWorksheet->getStyle('L')->getAlignment()->setWrapText('true');
            if($this->the_request['stock_report_group_by'] == 'code'){
                $activeWorksheet->getColumnDimension('M')->setWidth(15);//              oh
                $activeWorksheet->getStyle('M')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
            
        }
       
        $previous_group_id = '-1';
        $stock_no=1;
        foreach ($stock_temp as $stock) {
            $row = $activeWorksheet->getHighestRow()+1;
            
            if($this->the_request['stock_report_group_by']!=''){
                $this_group=$this->the_request['stock_report_group_by'];                               
            }

            if($previous_group_id != '-1' && ($this->the_request['stock_report_group_by']!='' ? $stock->$this_group != $previous_group_id && $this->the_request['stock_report_group_by'] != 'none':$this->the_request['stock_report_group_by'] != 'none')) {                
                //New Stock Group
                if($request['stock_report_format']!='flattened' && $stock_no!=1){
                    //Transactional
                    //Insert Group Total Line from Previous Group                
                    $activeWorksheet->setCellValue(($this->the_request['stock_report_group_by'] == 'code' ? 'M':'L').$row, number_format($group_qty_sum, 3));
                    $stock_no++;                 

                }
                
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
                      
                $activeWorksheet->getStyle('A'.$row.($request['stock_report_format']=='flattened' ? ':J':($this->the_request['stock_report_group_by'] == 'code' ? ':M':':L')).$row)->applyFromArray($styleArray);                               
                
                //Reset Group Totals            
                $group_qty_sum = 0.000;                

                $row = $activeWorksheet->getHighestRow()+1;                

            } 
                //Continuing a Stock Group / First Row 
                //Totaling Transaction Items                
                if($request['stock_report_format']!='flattened'){
                    $group_qty_sum = $group_qty_sum + (float)$stock->qty;
                } else {
                    $group_qty_sum = $group_qty_sum + (float)$stock->oh;
                }

                $insert_array = [
                    ($request['stock_report_format']=='flattened' ? $stock->code:$stock->date) ,//code    date
                    ($request['stock_report_format']=='flattened' ? $stock->description:$stock->type) ,//description   type
                    ($request['stock_report_format']=='flattened' ? number_format($stock->os,3):$stock->code) ,//os   code
                    ($request['stock_report_format']=='flattened' ? number_format($stock->rec,3):$stock->description) ,//rec description
                    ($request['stock_report_format']=='flattened' ? number_format($stock->ret,3):number_format($stock->qty,3)) ,//ret  qty
                    ($request['stock_report_format']=='flattened' ? number_format($stock->bat,3):$stock->comment) ,//bat   comment
                    ($request['stock_report_format']=='flattened' ? number_format($stock->jdisp,3):$stock->supplier),//jdisp   supplier
                    ($request['stock_report_format']=='flattened' ? number_format($stock->cdisp,3):$stock->customer),//cdisp    customer
                    ($request['stock_report_format']=='flattened' ? number_format($stock->adj,3):$stock->jobcard) ,//adj   jobcard  
                    ($request['stock_report_format']=='flattened' ? number_format($stock->oh,3):$stock->site),//oh   site
                    
                ];

                if($request['stock_report_format']!='flattened'){
                    //Transactional
                    array_push($insert_array, $stock->dispatch);                    
                    array_push($insert_array, $stock->reference);
                    if($this->the_request['stock_report_group_by'] == 'code'){
                        array_push($insert_array, number_format($group_qty_sum,3));
                    }
                    
                }              
                
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
                $activeWorksheet->getStyle('A'.$row.($request['stock_report_format']=='flattened' ? ':J':($this->the_request['stock_report_group_by'] == 'code' ? ':M':':L')).$row)->applyFromArray($styleArray);
                
                if($this->the_request['stock_report_group_by']!=''){
                    $this_group=$this->the_request['stock_report_group_by'];
                    $previous_group_id = $stock->$this_group;
                }
                
           

        }
        //Generate Last Stock Group Total Line        
        //Insert Group Total Line from Previous Group
        $row = $activeWorksheet->getHighestRow()+1;
        if($request['stock_report_format']!='flattened' && $stock_no!=1){
            //Transactional
            //Insert Group Total Line from Previous Group                
            $activeWorksheet->setCellValue(($this->the_request['stock_report_group_by'] == 'code' ? 'M':'L').$row, number_format($group_qty_sum, 3));                  
            $stock_no++;
        }                
        
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
        $activeWorksheet->getStyle('A'.$row.($request['stock_report_format']=='flattened' ? ':J':($this->the_request['stock_report_group_by'] == 'code' ? ':M':':L')).$row)->applyFromArray($styleArray);                
        
        //Reset Group Totals            
        $group_qty_sum = 0.000;        

        $row = $activeWorksheet->getHighestRow()+1;                 

        //Footer Row
        $footer_row = $activeWorksheet->getHighestRow()+2;
        $activeWorksheet->insertNewRowBefore($footer_row);
        $activeWorksheet->getStyle('A'.$footer_row.($request['stock_report_format']=='flattened' ? ':J':($this->the_request['stock_report_group_by'] == 'code' ? ':M':':J')).$footer_row)->getFont()->setSize(8);                
        $activeWorksheet->setCellValue(($request['stock_report_format']=='flattened' ? 'J':($this->the_request['stock_report_group_by'] == 'code' ? 'M':'J')).$footer_row, 'Report generated @'.date('Y-m-d h:i:s',time()));        
                
        $filename = ($request['stock_report_format']=='flattened' ? 'Summarised Report - ' : 'Transactional Report - ') . ucfirst($request['stock_report_category']) . ' Stock from ' . $request['from_date'] . ' to ' . $request['to_date'].' generated '.date('Ymd his',time()).'.xlsx';
        
        try {
            ob_end_clean();
        }
        finally
        {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            exit($xlsxWriter->save('php://output'));
        }
        
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
        'transactions.product_id as transactions_product_id','transactions.status as transactions_status','transactions.qty as qty','plant.plant_number as plant_number',
        'products.code as product_code', 'products.description as product_description', 'products.weighed_product as weighed_product')
        ->where(function($query){$query->where('dispatches.status', 'Dispatched')
            ->orWhere('dispatches.status', 'Returned')
            ->orWhere('dispatches.status', 'Partial Return')
            ->orWhere('dispatches.status', 'Transferred')
            ->orWhere('dispatches.status', 'Partial Transfer');
        })
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
        $activeWorksheet->mergeCells('A1:N1');
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
        $activeWorksheet->getStyle('A1:N1')->applyFromArray($styleArray);

        $activeWorksheet->setCellValue('A2', $report_title);        
        $activeWorksheet->mergeCells('A2:N2');
        $styleArray['font']['size']=12;
        $styleArray['font']['italic']=true;
        $styleArray['font']['bold']=false;
        $styleArray['borders']['top']=[''];
        $styleArray['borders']['bottom']['borderStyle']=Border::BORDER_MEDIUM;
        $styleArray['borders']['bottom']['color']['argb']='#000000';
        $activeWorksheet->getStyle('A2:N2')->applyFromArray($styleArray);
        
        //Column Headers
        $header_row_array = ['Document No', 'Type', 'Status', 'Date', 'Reference No', 'Registration No', 'Delivery Zone', 'Customer / Contractor Name', 'Jobcard', 'Site', 'Product Code', 'Product Name', 'Qty', 'Net Mass'];
        $activeWorksheet->fromArray( $header_row_array, NULL, 'A3' );
        $styleArray['font']['size']=11;
        $styleArray['font']['italic']=false;
        $styleArray['font']['bold']=true;
        $styleArray['alignment']['horizontal']=Alignment::HORIZONTAL_LEFT;        
        $activeWorksheet->getStyle('A3:N3')->applyFromArray($styleArray);                
        
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
        $activeWorksheet->getColumnDimension('J')->setWidth(10);
        $activeWorksheet->getColumnDimension('K')->setWidth(13);
        $activeWorksheet->getColumnDimension('L')->setWidth(41);
        $activeWorksheet->getStyle('M:N')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $activeWorksheet->getColumnDimension('M')->setWidth(10);
        $activeWorksheet->getColumnDimension('N')->setWidth(10);
       
        $previous_group_id = '-1';

        foreach ($the_report_dispatches as $dispatch) {
            // dd($dispatch);
            $row = $activeWorksheet->getHighestRow()+1;

            if($previous_group_id != '-1' && ($dispatch[$this->the_request['dispatch_report_group_by']]  != $previous_group_id && $this->the_request['dispatch_report_group_by'] != 'none')) {                
                //New Dispatch Group
                //Insert Group Total Line from Previous Group                
                $activeWorksheet->setCellValue('M'.$row, number_format($group_qty_sum, 3));
                $activeWorksheet->setCellValue('N'.$row, number_format($group_mass_sum, 3));
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
                      
                $activeWorksheet->getStyle('A'.$row.':N'.$row)->applyFromArray($styleArray);
                $styleArray['borders']['left']=[''];
                $styleArray['borders']['top']['borderStyle']=Border::BORDER_MEDIUM;
                $styleArray['borders']['top']['color']['argb']='#000000';
                $activeWorksheet->getStyle('M'.$row.':N'.$row)->applyFromArray($styleArray);                
                
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
                    ($dispatch['status'] == 'Dispatched' ? 'Dispatch':($dispatch['status'] == 'Returned' ? 'Return':'')),
                    $dispatch['status'],
                    $dispatch['weight_out_datetime'],
                    $dispatch['reference'],
                    (/* strlen($dispatch['registration_number']) == 0 &&  */$dispatch['plant_id'] > 0 ? $dispatch['plant_number'] : (strlen($dispatch['outsourced_contractor']) != 0 ?  $dispatch['registration_number']."*" : $dispatch['registration_number'])),
                    ($dispatch['delivery_zone'] != '0' ? $dispatch['delivery_zone']:''),
                    ($dispatch['customer_id'] == '0' ? ucfirst($dispatch['contractor_name']):ucfirst($dispatch['customer_name'])),
                    ($dispatch['job_id'] != '0' ? $dispatch['jobcard_number']:''),
                    ($dispatch['job_id'] != '0' ? $dispatch['site_number']:''),
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
                $activeWorksheet->getStyle('A'.$row.':N'.$row)->applyFromArray($styleArray);

                $previous_group_id = $dispatch[$this->the_request['dispatch_report_group_by']];
                // Functions::console_log($previous_group_id);
           

        }
        //Generate Last Dispatch Group Total Line        
        //Insert Group Total Line from Previous Group
        $row = $activeWorksheet->getHighestRow()+1;                
        $activeWorksheet->setCellValue('M'.$row, number_format($group_qty_sum, 3));
        $activeWorksheet->setCellValue('N'.$row, number_format($group_mass_sum, 3));
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
        $activeWorksheet->getStyle('A'.$row.':N'.$row)->applyFromArray($styleArray);                
        
        //Reset Group Totals            
        $group_qty_sum = 0.000;
        $group_mass_sum = 0.000;

        $row = $activeWorksheet->getHighestRow()+1;        

        //Grand Totals Line        
        if(count($the_report_dispatches)>0){
            $last_row = $activeWorksheet->getHighestRow()+2;
            $activeWorksheet->insertNewRowBefore($last_row); 
            $activeWorksheet->setCellValue('L'.$last_row, 'Grand Totals');
            $activeWorksheet->setCellValue('M'.$last_row, number_format($total_qty_sum, 3));
            $activeWorksheet->setCellValue('N'.$last_row, number_format($total_mass_sum, 3));
            $styleArray = [];
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['argb' => '#000000'],
                    ],
                ],
            ];                
            $activeWorksheet->getStyle('L'.$last_row.':N'.$last_row)->applyFromArray($styleArray);
            $activeWorksheet->getStyle('L'.$last_row.':'.'N'.$last_row)->getFont()->setBold( true );                    
        } else {
            $last_row = $activeWorksheet->getHighestRow()+1;
            $activeWorksheet->insertNewRowBefore($last_row);
            $activeWorksheet->setCellValue('A'.$last_row, 'Nothing to list matching the provided parameters...');                 
        }      

        //Footer Row
        $footer_row = $activeWorksheet->getHighestRow()+2;
        $activeWorksheet->insertNewRowBefore($footer_row);
        $activeWorksheet->getStyle('A'.$footer_row.':N'.$footer_row)->getFont()->setSize(8);
        $activeWorksheet->setCellValue('A'.$footer_row, '* Outsourced Contractor Used');        
        $activeWorksheet->setCellValue('N'.$footer_row, 'Report generated @'.date('Y-m-d h:i:s',time()));        
                
        $filename = 'Transaction Report-' . ucfirst($request['dispatch_report_category']) . ' Clients from ' . $request['from_date'] . ' to ' . $request['to_date'].' generated '.date('Ymd his',time()).'.xlsx';
        
        try {
            ob_end_clean();
        }
        finally
        {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');

            $xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            exit($xlsxWriter->save('php://output'));
        }

        
    }    

    function report_dispatch()
    {        
        return view('manufacture.report.report_dispatch');
    }
}


