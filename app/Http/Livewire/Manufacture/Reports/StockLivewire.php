<?php

namespace App\Http\Livewire\Manufacture\Reports;

use Livewire\Component;
use App\Models\ManufactureProducts;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureProductTransactions;

class StockLivewire extends Component
{
    public $stock_report_category,
        $stock_report_group_by = 'none',
        $from_date,        
        $to_date,
        $stock_report_format = 'flattened',
        $extra_criteria_enabled = false,
        $extra_criteria = 0,
        $supplier_name_filter = 0, 
        $ref_number_filter = 0,       
        $product_description_filter = 0,
        $primary_filter,
        $primary_filter_column,        
        $primary_filter_text,
        $supplier_name_filter_old,
        $secondary_filter,
        $secondary_filter_column,        
        $secondary_filter_text,
        $ref_number_filter_old,        
        $tertiary_filter,
        $tertiary_filter_column,        
        $tertiary_filter_text,
        $product_description_filter_old,
        $stock_report_supplier_list = [],         
        $stock_report_reference_list = [],
        $stock_report_product_list = [];  
        
    
    function updatedFromDate(){
        if($this->to_date < $this->from_date){
            $this->to_date = $this->from_date;
        }
    }        

    function updatedToDate(){
        if($this->from_date > $this->to_date){
            $this->from_date = $this->to_date;
        }
    }

    function resetForm (){
        // dd('resettting the form?');
        $this->stock_report_category = 0;
        $this->stock_report_group_by = 'none';
        $this->stock_report_format = 'flattened';
        $this->from_date = '';
        $this->to_date = '';
        $this->extra_criteria_enabled = false;
        $this->extra_criteria = 0;
        //Reset Additional Filter Criteria
        $this->resetAdditionals();       
    }

    function resetAdditionals(){
        $this->supplier_name_filter = 0;        
        $this->product_description_filter = 0;
        $this->primary_filter = '';
        $this->primary_filter_column = '';        
        $this->primary_filter_text = '';
        $this->supplier_name_filter_old = '';
        $this->secondary_filter = '';
        $this->secondary_filter_column = '';        
        $this->secondary_filter_text = '';        
        $this->tertiary_filter = '';
        $this->tertiary_filter_column = '';        
        $this->tertiary_filter_text = '';
        $this->product_description_filter_old = '';
        $this->stock_report_supplier_list = [];        
        $this->stock_report_product_list = [];
        $this->stock_report_reference_list = [];
    }

    function updatedSupplierNameFilter(){        
        if($this->supplier_name_filter != '0'){
            //New value is not All
            if($this->supplier_name_filter_old != '0' && $this->supplier_name_filter_old != $this->supplier_name_filter){
                if($this->primary_filter == 'supplier'){                    
                    $this->primary_filter_text = $this->supplier_name_filter;
                } elseif ($this->secondary_filter == 'supplier'){                    
                    $this->secondary_filter_text = $this->supplier_name_filter;
                } elseif ($this->tertiary_filter == 'supplier'){                   
                    $this->tertiary_filter_text = $this->supplier_name_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'supplier';
                    $this->primary_filter_column = 'type_id';
                    $this->primary_filter_text = $this->supplier_name_filter;
                    
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'supplier';
                    $this->secondary_filter_column = 'type_id';
                    $this->secondary_filter_text = $this->supplier_name_filter;
                    
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'supplier';                
                    $this->tertiary_filter_column = 'type_id';
                    $this->tertiary_filter_text = $this->supplier_name_filter;                    
                }
            }
        } else {
            if($this->primary_filter == 'supplier'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;                        
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    }                    
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';
                }
                                
            } elseif ($this->secondary_filter == 'supplier'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'supplier'){                                          
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        }               
    }    

    function updatedProductDescriptionFilter(){
        if($this->product_description_filter != '0'){
            //New value is not All
            if($this->product_description_filter_old != '0' && $this->product_description_filter_old != $this->product_description_filter){
                if($this->primary_filter == 'product'){                    
                    $this->primary_filter_text = $this->product_description_filter;
                } elseif ($this->secondary_filter == 'product'){                    
                    $this->secondary_filter_text = $this->product_description_filter;
                } elseif ($this->tertiary_filter == 'product'){                   
                    $this->tertiary_filter_text = $this->product_description_filter;
                } 
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'product';                    
                    $this->primary_filter_text = $this->product_description_filter;
                    $this->primary_filter_column = 'manufacture_product_transactions.product_id';                    
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'product';                    
                    $this->secondary_filter_text = $this->product_description_filter;
                    $this->secondary_filter_column = 'manufacture_product_transactions.product_id';
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'product';                
                    $this->tertiary_filter_column = 'manufacture_product_transactions.product_id';
                    $this->tertiary_filter_text = $this->product_description_filter;                    
                }
            }
        } else {            
            if($this->primary_filter == 'product'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;                        
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    }                    
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';                    
                }
            
            } elseif ($this->secondary_filter == 'product'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'product'){                                          
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        } 
        // dd($this->product_description_filter);       
    }

    function updatedRefNumberFilter(){
        if($this->ref_number_filter != '0'){
            //New value is not All
            if($this->ref_number_filter_old != '0' && $this->ref_number_filter_old != $this->ref_number_filter){
                if($this->primary_filter == 'ref'){                    
                    $this->primary_filter_text = $this->ref_number_filter;
                } elseif ($this->secondary_filter == 'ref'){                    
                    $this->secondary_filter_text = $this->ref_number_filter;
                } elseif ($this->tertiary_filter == 'ref'){                   
                    $this->tertiary_filter_text = $this->ref_number_filter;
                } 
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'ref';                    
                    $this->primary_filter_text = $this->ref_number_filter;
                    $this->primary_filter_column = 'manufacture_product_transactions.reference_number';                    
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'ref';                    
                    $this->secondary_filter_text = $this->ref_number_filter;
                    $this->secondary_filter_column = 'manufacture_product_transactions.reference_number';
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'ref';                
                    $this->tertiary_filter_column = 'manufacture_product_transactions.reference_number';
                    $this->tertiary_filter_text = $this->ref_number_filter;                    
                }
            }
        } else {            
            if($this->primary_filter == 'ref'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;                        
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';    
                    }                    
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';                    
                }
            
            } elseif ($this->secondary_filter == 'ref'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'ref'){                                          
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            }
        } 
        // dd($this->ref_number_filter);       
    }

    public function buildSelects($stock_list){
        
        // dd($stock_list);
        $stock_report_supplier_list = []; 
        $stock_report_reference_list = [];       
        $stock_report_product_list = [];
        
        //Suppliers
        foreach($stock_list as $item){            
            if(($item->type_id != '0') && ($item->type == 'REC' || $item->type == 'RET') ){array_unshift($stock_report_supplier_list, ['value' => $item->type_id, 'name' => $item->supplier_name]);}            
        }

        //Remove duplicate & blanks Suppliers        
        $seenItems = array();
        foreach($stock_report_supplier_list as $index => $item){
            if(in_array($item["value"], $seenItems) || $item["value"]=='')
                unset($stock_report_supplier_list[$index]);
            else
                $seenItems[] = $item["value"];
        }
        
        //References
        foreach($stock_list as $item){
            array_unshift($stock_report_reference_list, ['value' => $item->reference_number, 'name' => $item->reference_number]);
        }

        //Remove duplicate & blank References
        $seenItems = array();
        foreach($stock_report_reference_list as $index => $item){
            if(in_array($item["value"], $seenItems) || $item["value"]=='')
                unset($stock_report_reference_list[$index]);
            else
                $seenItems[] = $item["value"];
        }

        //Products
        foreach($stock_list as $item){                       
            if($item->transactions_product_id != '0'){array_unshift($stock_report_product_list, ['value' => $item->transactions_product_id, 'name' => $item->code.'-'.$item->description]);}          
        }       

        //Remove duplicate Products        
        $seenItems = array();
        foreach($stock_report_product_list as $index => $item){
            if(in_array($item["value"], $seenItems) || $item["value"]=='')
                unset($stock_report_product_list[$index]);
            else
                $seenItems[] = $item["value"];
        }
        
        //return sorted lists               
        
        $value  = array_column($stock_report_supplier_list, 'value');
        $name = array_column($stock_report_supplier_list, 'name');
        $this->stock_report_supplier_list = $stock_report_supplier_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->stock_report_supplier_list);
        array_unshift($this->stock_report_supplier_list, ['value' => '0', 'name' => 'All']);
        
        $value  = array_column($stock_report_reference_list, 'value');
        $name = array_column($stock_report_reference_list, 'name');
        $this->stock_report_reference_list = $stock_report_reference_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->stock_report_reference_list);
        array_unshift($this->stock_report_reference_list, ['value' => '0', 'name' => 'All']);

        $value  = array_column($stock_report_product_list, 'value');
        $name = array_column($stock_report_product_list, 'name');
        $this->stock_report_product_list = $stock_report_product_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->stock_report_product_list);
        array_unshift($this->stock_report_product_list, ['value' => '0', 'name' => 'All']);
        
    }    

    function updatedExtraCriteria(){
        //Reset addtional filters on toggle
        if ($this->extra_criteria == false){            
            $this->resetAdditionals();
        }

    }
    
    public function render()
    {
        $stock_report_category_list = SelectLists::stock_report_categories;
        array_unshift($stock_report_category_list, ['value' => 0, 'name' => 'Select']);        
                
        $this->supplier_name_filter_old = $this->supplier_name_filter;        
        $this->ref_number_filter_old = $this->ref_number_filter;
        $this->product_description_filter_old = $this->product_description_filter;

        if(isset($this->from_date) && isset($this->to_date) && $this->stock_report_category != 0){
            $this->extra_criteria_enabled = true;
        }

        if($this->extra_criteria == true){
            //there is now extra criteria enabled
            
            //Transactions in Range
            $stock_list = ManufactureProductTransactions::join('manufacture_suppliers', 'manufacture_suppliers.id', '=', 'manufacture_product_transactions.type_id', 'left outer')
            ->join('manufacture_products', 'manufacture_products.id', '=', 'manufacture_product_transactions.product_id', 'left outer')                
            ->select('manufacture_product_transactions.id as id','manufacture_product_transactions.type as type','manufacture_product_transactions.type_id as type_id','manufacture_product_transactions.reference_number'
            ,'manufacture_product_transactions.registration_number as registration_number','manufacture_suppliers.name as supplier_name','manufacture_products.code as code','manufacture_products.description as description'
            ,'manufacture_product_transactions.qty as qty','manufacture_product_transactions.product_id as transactions_product_id','manufacture_products.has_recipe as has_recipe'
            ,'manufacture_products.weighed_product as weighed_product','manufacture_product_transactions.weight_out_datetime as weight_out_datetime')
            //All Status except Blank, NULL and Loading
            ->where('manufacture_product_transactions.status', '<>', '')
            ->where('manufacture_product_transactions.status', '<>', 'Loading')
            ->where('manufacture_product_transactions.status', '<>', NULL)            
            ->where('manufacture_product_transactions.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
            ->where('manufacture_product_transactions.weight_out_datetime', '<=', $this->to_date.' 23:59:59')
            ->where (function($query){
                if($this->primary_filter != ''){
                    // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                    $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')                        
                    /* ->orWhere($this->primary_filter_column2, 'like', '%'.$this->primary_filter_text.'%') */->where
                    (function($query){
                        if($this->secondary_filter != ''){
                            $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%');
                            
                        }
                    });
                }
            })
            ->where (function($query){
                if($this->stock_report_category == 'raw'){
                    $query->where('manufacture_products.has_recipe', '0');
                }
                if($this->stock_report_category == 'manufactured'){
                    $query->where('manufacture_products.has_recipe', '1');
                }
            })            
            ->get();
           /*  $query = str_replace(array('?'), array('\'%s\''), $stock_list->toSql());
            $query = vsprintf($query, $stock_list->getBindings());
            dd($query); */

            // dd($stock_list);
            $this->buildSelects($stock_list);            
            
        }        

        return view('livewire.manufacture.reports.stock-livewire', ['stock_report_category_list' => $stock_report_category_list]);
    }
}