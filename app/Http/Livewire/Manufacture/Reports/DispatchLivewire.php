<?php

namespace App\Http\Livewire\Manufacture\Reports;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureJobcards;
use App\Http\Controllers\SelectLists;
use App\Models\ManufactureJobcardProductDispatches;

class DispatchLivewire extends Component
{
    
    public $dispatch_report_category,
        $from_date,        
        $to_date,
        $extra_criteria_enabled = false,
        $extra_criteria = 0,
        $job_number_filter = 0,
        $site_number_filter = 0,
        $ref_number_filter = 0,
        $customer_name_filter = 0,
        $account_number_filter = 0,
        $product_description_filter = 0,
        $primary_filter,
        $primary_filter_column,
        $primary_filter_column2,
        $primary_filter_text,
        $job_number_filter_old,
        $secondary_filter,
        $secondary_filter_column,
        $secondary_filter_column2,
        $secondary_filter_text,
        $site_number_filter_old,
        $tertiary_filter,
        $tertiary_filter_column,
        $tertiary_filter_column2,
        $tertiary_filter_text,
        $ref_number_filter_old,
        $quaternary_filter,
        $quaternary_filter_column,
        $quaternary_filter_column2,
        $quaternary_filter_text,
        $customer_name_filter_old,
        $quinary_filter,
        $quinary_filter_column,
        $quinary_filter_column2,
        $quinary_filter_text,
        $account_number_filter_old,
        $senary_filter,
        $senary_filter_column,
        $senary_filter_column2,
        $senary_filter_text,
        $product_description_filter_old,
        $dispatch_report_jobcard_list = [],
        $dispatch_report_reference_list = [], 
        $dispatch_report_site_list = [],
        $dispatch_report_customer_list = [],
        $dispatch_report_account_list = [], 
        $dispatch_report_product_list = [];  
        
    
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
        $this->dispatch_report_category = 0;
        $this->from_date = '';
        $this->to_date = '';
        $this->extra_criteria_enabled = false;
        $this->extra_criteria = 0;
        //Reset Additional Filter Criteria
        $this->resetAdditionals();

       /*  $dispatch_report_category_list = SelectLists::dispatch_report_categories;
        array_unshift($dispatch_report_category_list, ['value' => 0, 'name' => 'Select']);

        return view('livewire.manufacture.reports.dispatch-livewire', ['dispatch_report_category_list' => $dispatch_report_category_list]); */
    }

    function resetAdditionals(){
        $this->job_number_filter = 0;
        $this->site_number_filter = 0;
        $this->ref_number_filter = 0;
        $this->customer_name_filter = 0;
        $this->account_number_filter = 0;
        $this->product_description_filter = 0;
        $this->primary_filter = '';
        $this->primary_filter_column = '';
        $this->primary_filter_column2 = '';
        $this->primary_filter_text = '';
        $this->job_number_filter_old = '';
        $this->secondary_filter = '';
        $this->secondary_filter_column = '';
        $this->secondary_filter_column2 = '';
        $this->secondary_filter_text = '';
        $this->site_number_filter_old = '';
        $this->tertiary_filter = '';
        $this->tertiary_filter_column = '';
        $this->tertiary_filter_column2 = '';
        $this->tertiary_filter_text = '';
        $this->ref_number_filter_old = '';
        $this->quaternary_filter = '';
        $this->quaternary_filter_column = '';
        $this->quaternary_filter_column2 = '';
        $this->quaternary_filter_text = '';
        $this->customer_name_filter_old = '';
        $this->quinary_filter = '';
        $this->quinary_filter_column = '';
        $this->quinary_filter_column2 = '';
        $this->quinary_filter_text = '';
        $this->account_number_filter_old = '';
        $this->senary_filter = '';
        $this->senary_filter_column = '';
        $this->senary_filter_column2 = '';
        $this->senary_filter_text = '';
        $this->product_description_filter_old = '';
        $this->dispatch_report_jobcard_list = [];
        $this->dispatch_report_reference_list = [];
        $this->dispatch_report_site_list = [];
        $this->dispatch_report_customer_list = [];
        $this->dispatch_report_account_list = [];
        $this->dispatch_report_product_list = [];
    }

    function updatedJobNumberFilter(){        
        if($this->job_number_filter != '0'){
            //New value is not All
            if($this->job_number_filter_old != '0' && $this->job_number_filter_old != $this->job_number_filter){
                if($this->primary_filter == 'job'){                    
                    $this->primary_filter_text = $this->job_number_filter;
                } elseif ($this->secondary_filter == 'job'){                    
                    $this->secondary_filter_text = $this->job_number_filter;
                } elseif ($this->tertiary_filter == 'job'){                   
                    $this->tertiary_filter_text = $this->job_number_filter;
                } elseif ($this->quaternary_filter == 'job'){                   
                    $this->quaternary_filter_text = $this->job_number_filter;
                } elseif ($this->quinary_filter == 'job'){                   
                    $this->quinary_filter_text = $this->job_number_filter;
                } elseif ($this->senary_filter == 'job'){                   
                    $this->senary_filter_text = $this->job_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'job';
                    $this->primary_filter_column = 'job_id';
                    $this->primary_filter_text = $this->job_number_filter;
                    $this->primary_filter_column2 = '';
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'job';
                    $this->secondary_filter_column = 'job_id';
                    $this->secondary_filter_text = $this->job_number_filter;
                    $this->secondary_filter_column2 = '';
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'job';                
                    $this->tertiary_filter_column = 'job_id';
                    $this->tertiary_filter_text = $this->job_number_filter;
                    $this->tertiary_filter_column2 = '';
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'job';                
                    $this->quaternary_filter_column = 'job_id';
                    $this->quaternary_filter_text = $this->job_number_filter;
                    $this->quaternary_filter_column2 = '';
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'job';                
                    $this->quinary_filter_column = 'job_id';
                    $this->quinary_filter_text = $this->job_number_filter;
                    $this->quinary_filter_column2 = '';
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'job';                
                    $this->senary_filter_column = 'job_id';
                    $this->senary_filter_text = $this->job_number_filter;
                    $this->senary_filter_column2 = '';
                }
            }
        } else {
            if($this->primary_filter == 'job'){
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
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = '';    
                        }
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
                                
            } elseif ($this->secondary_filter == 'job'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = '';                    
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'job'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                }
            } elseif ($this->quaternary_filter == 'job'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;
                    $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;                
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                    }                       
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                }
            } elseif ($this->quinary_filter == 'job'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';
                    $this->senary_filter_text = '';                       
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';
                    $this->quinary_filter_text = '';
                }
            } elseif ($this->senary_filter == 'job'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';                
            }
        }               
    }

    function updatedSiteNumberFilter(){
        if($this->site_number_filter != '0'){
            //New value is not All
            if($this->site_number_filter_old != '0' && $this->site_number_filter_old != $this->site_number_filter){
                if($this->primary_filter == 'site'){                    
                    $this->primary_filter_text = $this->site_number_filter;
                } elseif ($this->secondary_filter == 'site'){                    
                    $this->secondary_filter_text = $this->site_number_filter;
                } elseif ($this->tertiary_filter == 'site'){                   
                    $this->tertiary_filter_text = $this->site_number_filter;
                } elseif ($this->quaternary_filter == 'site'){                   
                    $this->quaternary_filter_text = $this->site_number_filter;
                } elseif ($this->quinary_filter == 'site'){                   
                    $this->quinary_filter_text = $this->site_number_filter;
                } elseif ($this->senary_filter == 'site'){                   
                    $this->senary_filter_text = $this->site_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'site';
                    $this->primary_filter_column = 'site_number';
                    $this->primary_filter_text = $this->site_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'site';
                    $this->secondary_filter_column = 'site_number';
                    $this->secondary_filter_text = $this->site_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'site';                
                    $this->tertiary_filter_column = 'site_number';
                    $this->tertiary_filter_text = $this->site_number_filter;
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'site';                
                    $this->quaternary_filter_column = 'site_number';
                    $this->quaternary_filter_text = $this->site_number_filter;
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'site';                
                    $this->quinary_filter_column = 'site_number';
                    $this->quinary_filter_text = $this->site_number_filter;
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'site';                
                    $this->senary_filter_column = 'site_number';
                    $this->senary_filter_text = $this->site_number_filter;
                }
            }
        } else {
            if($this->primary_filter == 'site'){
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
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = '';    
                        }
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
                                
            } elseif ($this->secondary_filter == 'site'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = '';                    
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'site'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                }
            } elseif ($this->quaternary_filter == 'site'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;
                    $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;                
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                    }                       
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                }
            } elseif ($this->quinary_filter == 'site'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';
                    $this->senary_filter_text = '';                       
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';
                    $this->quinary_filter_text = '';
                }
            } elseif ($this->senary_filter == 'site'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';                
            }
        }            
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
                } elseif ($this->quaternary_filter == 'ref'){                   
                    $this->quaternary_filter_text = $this->ref_number_filter;
                } elseif ($this->quinary_filter == 'ref'){                   
                    $this->quinary_filter_text = $this->ref_number_filter;
                } elseif ($this->senary_filter == 'ref'){                   
                    $this->senary_filter_text = $this->ref_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'ref';
                    $this->primary_filter_column = 'reference';
                    $this->primary_filter_text = $this->ref_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'ref';
                    $this->secondary_filter_column = 'reference';
                    $this->secondary_filter_text = $this->ref_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'ref';                
                    $this->tertiary_filter_column = 'reference';
                    $this->tertiary_filter_text = $this->ref_number_filter;
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'ref';                
                    $this->quaternary_filter_column = 'reference';
                    $this->quaternary_filter_text = $this->ref_number_filter;
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'ref';                
                    $this->quinary_filter_column = 'reference';
                    $this->quinary_filter_text = $this->ref_number_filter;
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'ref';                
                    $this->senary_filter_column = 'reference';
                    $this->senary_filter_text = $this->ref_number_filter;
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
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = '';    
                        }
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
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = '';                    
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'ref'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                }
            } elseif ($this->quaternary_filter == 'ref'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;
                    $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;                
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                    }                       
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                }
            } elseif ($this->quinary_filter == 'ref'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';
                    $this->senary_filter_text = '';                       
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';
                    $this->quinary_filter_text = '';
                }
            } elseif ($this->senary_filter == 'ref'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';                
            }
        }        
    }

    function updatedCustomerNameFilter(){
        if($this->customer_name_filter != '0'){
            //New value is not All
            if($this->customer_name_filter_old != '0' && $this->customer_name_filter_old != $this->customer_name_filter){
                if($this->primary_filter == 'customer'){                    
                    $this->primary_filter_text = $this->customer_name_filter;
                } elseif ($this->secondary_filter == 'customer'){                    
                    $this->secondary_filter_text = $this->customer_name_filter;
                } elseif ($this->tertiary_filter == 'customer'){                   
                    $this->tertiary_filter_text = $this->customer_name_filter;
                } elseif ($this->quaternary_filter == 'customer'){                   
                    $this->quaternary_filter_text = $this->customer_name_filter;
                } elseif ($this->quinary_filter == 'customer'){                   
                    $this->quinary_filter_text = $this->customer_name_filter;
                } elseif ($this->senary_filter == 'customer'){                   
                    $this->senary_filter_text = $this->customer_name_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'customer';
                    $this->primary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->primary_filter_text = $this->customer_name_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'customer';
                    $this->secondary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->secondary_filter_text = $this->customer_name_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'customer';                
                    $this->tertiary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->tertiary_filter_text = $this->customer_name_filter;
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'customer';                
                    $this->quaternary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->quaternary_filter_text = $this->customer_name_filter;
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'customer';                
                    $this->quinary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->quinary_filter_text = $this->customer_name_filter;
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'customer';                
                    $this->senary_filter_column = 'manufacture_jobcard_product_dispatches.customer_id';
                    $this->senary_filter_text = $this->customer_name_filter;
                }
            }
        } else {            
            if($this->primary_filter == 'customer'){
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
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = '';    
                        }
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
                                
            } elseif ($this->secondary_filter == 'customer'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = '';                    
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'customer'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                }
            } elseif ($this->quaternary_filter == 'customer'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;
                    $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;                
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                    }                       
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                }
            } elseif ($this->quinary_filter == 'customer'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';
                    $this->senary_filter_text = '';                       
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';
                    $this->quinary_filter_text = '';
                }
            } elseif ($this->senary_filter == 'customer'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';                
            }
        }
        
        // dd($this->customer_name_filter);
    }

    function updatedAccountNumberFilter(){
        if($this->account_number_filter != '0'){
            //New value is not All
            if($this->account_number_filter_old != '0' && $this->account_number_filter_old != $this->account_number_filter){
                if($this->primary_filter == 'account'){                    
                    $this->primary_filter_text = $this->account_number_filter;
                } elseif ($this->secondary_filter == 'account'){                    
                    $this->secondary_filter_text = $this->account_number_filter;
                } elseif ($this->tertiary_filter == 'account'){                   
                    $this->tertiary_filter_text = $this->account_number_filter;
                } elseif ($this->quaternary_filter == 'account'){                   
                    $this->quaternary_filter_text = $this->account_number_filter;
                } elseif ($this->quinary_filter == 'account'){                   
                    $this->quinary_filter_text = $this->account_number_filter;
                } elseif ($this->senary_filter == 'account'){                   
                    $this->senary_filter_text = $this->account_number_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'account';
                    $this->primary_filter_column = 'account_number';
                    $this->primary_filter_text = $this->account_number_filter;
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'account';
                    $this->secondary_filter_column = 'account_number';
                    $this->secondary_filter_text = $this->account_number_filter;
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'account';                
                    $this->tertiary_filter_column = 'account_number';
                    $this->tertiary_filter_text = $this->account_number_filter;
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'account';                
                    $this->quaternary_filter_column = 'account_number';
                    $this->quaternary_filter_text = $this->account_number_filter;
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'account';                
                    $this->quinary_filter_column = 'account_number';
                    $this->quinary_filter_text = $this->account_number_filter;
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'account';                
                    $this->senary_filter_column = 'account_number';
                    $this->senary_filter_text = $this->account_number_filter;
                }
            }
        } else {            
            if($this->primary_filter == 'account'){
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
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = '';    
                        }
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
                                
            } elseif ($this->secondary_filter == 'account'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text;                    
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = '';                    
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';                    
                }               
            } elseif ($this->tertiary_filter == 'account'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;                                                
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                }
            } elseif ($this->quaternary_filter == 'account'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;
                    $this->quaternary_filter_text = $this->quinary_filter_text;                                                
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;                
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                    }                       
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                }
            } elseif ($this->quinary_filter == 'account'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';
                    $this->senary_filter_text = '';                       
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';
                    $this->quinary_filter_text = '';
                }
            } elseif ($this->senary_filter == 'account'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';                
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
                } elseif ($this->quaternary_filter == 'product'){                   
                    $this->quaternary_filter_text = $this->product_description_filter;
                } elseif ($this->quinary_filter == 'product'){                   
                    $this->quinary_filter_text = $this->product_description_filter;
                } elseif ($this->senary_filter == 'product'){                   
                    $this->senary_filter_text = $this->product_description_filter;
                }
            } else {
                if($this->primary_filter == ''){
                    $this->primary_filter = 'product';
                    $this->primary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->primary_filter_text = $this->product_description_filter;
                    $this->primary_filter_column2 = 'manufacture_product_transactions.product_id';                    
                } elseif ($this->secondary_filter == ''){
                    $this->secondary_filter = 'product';
                    $this->secondary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->secondary_filter_text = $this->product_description_filter;
                    $this->secondary_filter_column2 = 'manufacture_product_transactions.product_id';
                } elseif ($this->tertiary_filter == ''){
                    $this->tertiary_filter = 'product';                
                    $this->tertiary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->tertiary_filter_text = $this->product_description_filter;
                    $this->tertiary_filter_column2 = 'manufacture_product_transactions.product_id';
                } elseif ($this->quaternary_filter == ''){
                    $this->quaternary_filter = 'product';                
                    $this->quaternary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->quaternary_filter_text = $this->product_description_filter;
                    $this->quaternary_filter_column2 = 'manufacture_product_transactions.product_id';
                } elseif ($this->quinary_filter == ''){
                    $this->quinary_filter = 'product';                
                    $this->quinary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->quinary_filter_text = $this->product_description_filter;
                    $this->quinary_filter_column2 = 'manufacture_product_transactions.product_id';
                } elseif ($this->senary_filter == ''){
                    $this->senary_filter = 'product';                
                    $this->senary_filter_column = 'manufacture_jobcard_product_dispatches.product_id';
                    $this->senary_filter_text = $this->product_description_filter;
                    $this->senary_filter_column2 = 'manufacture_jobcard_product_transactions.product_id';
                }
            }
        } else {            
            if($this->primary_filter == 'product'){
                //promote secondary if populated
                if ($this->secondary_filter != ''){
                    $this->primary_filter = $this->secondary_filter;
                    $this->primary_filter_column = $this->secondary_filter_column;
                    $this->primary_filter_text = $this->secondary_filter_text;
                    $this->primary_filter_column2 = $this->secondary_filter_column2;
                    //promote tertiary if populated
                    if ($this->tertiary_filter != ''){
                        $this->secondary_filter = $this->tertiary_filter;                
                        $this->secondary_filter_column = $this->tertiary_filter_column;
                        $this->secondary_filter_text = $this->tertiary_filter_text;
                        $this->secondary_filter_column2 = $this->tertiary_filter_column2;                      
                    
                        //promote quaternary if populated
                        if ($this->quarternary_filter != ''){
                            $this->tertiary_filter = $this->quarternary_filter;                
                            $this->tertiary_filter_column = $this->quarternary_filter_column;
                            $this->tertiary_filter_text = $this->quarternary_filter_text;
                            $this->tertiary_filter_column2 = $this->quarternary_filter_column2;                                                
                        
                            //promote quinary if populated
                            if ($this->quinary_filter != ''){
                                $this->quaternary_filter = $this->quinary_filter;                
                                $this->quaternary_filter_column = $this->quinary_filter_column;
                                $this->quaternary_filter_text = $this->quinary_filter_text;
                                $this->quaternary_filter_column2 = $this->quinary_filter_column2;                                                
                            
                                    //promote senary if populated
                                    if ($this->senary_filter != ''){
                                        $this->quinary_filter = $this->senary_filter;                
                                        $this->quinary_filter_column = $this->senary_filter_column;
                                        $this->quinary_filter_text = $this->senary_filter_text;
                                        $this->quinary_filter_column2 = $this->senary_filter_column2;
                                        
                                        //clear senary filter
                                        $this->senary_filter = '';                
                                        $this->senary_filter_column = '';
                                        $this->senary_filter_text = '';
                                        $this->senary_filter_column2 = '';
                                    }
                            } else {
                                $this->quaternary_filter = '';                
                                $this->quaternary_filter_column = '';
                                $this->quaternary_filter_text = '';
                                $this->quaternary_filter_column2 = '';    
                            }    
                        } else {
                            $this->tertiary_filter = '';                
                            $this->tertiary_filter_column = '';
                            $this->tertiary_filter_text = ''; 
                            $this->tertiary_filter_column2 = '';   
                        }
                    } else {
                        $this->secondary_filter = '';                
                        $this->secondary_filter_column = '';
                        $this->secondary_filter_text = '';
                        $this->secondary_filter_column2 = '';    
                    }
                } else {
                    $this->primary_filter = '';
                    $this->primary_filter_column = '';
                    $this->primary_filter_text = '';
                    $this->primary_filter_column2 = '';
                }
                                
            } elseif ($this->secondary_filter == 'product'){                
                //promote tertiary if populated
                if ($this->tertiary_filter != ''){
                    $this->secondary_filter = $this->tertiary_filter;                
                    $this->secondary_filter_column = $this->tertiary_filter_column;
                    $this->secondary_filter_text = $this->tertiary_filter_text; 
                    $this->secondary_filter_column2 = $this->tertiary_filter_column2;                   
                
                    //promote quaternary if populated
                    if ($this->quarternary_filter != ''){
                        $this->tertiary_filter = $this->quarternary_filter;                
                        $this->tertiary_filter_column = $this->quarternary_filter_column;
                        $this->tertiary_filter_text = $this->quarternary_filter_text;
                        $this->tertiary_filter_column2 = $this->quarternary_filter_column2;                                                
                    
                        //promote quinary if populated
                        if ($this->quinary_filter != ''){
                            $this->quaternary_filter = $this->quinary_filter;                
                            $this->quaternary_filter_column = $this->quinary_filter_column;
                            $this->quaternary_filter_text = $this->quinary_filter_text;
                            $this->quaternary_filter_column2 = $this->quinary_filter_column2;                                                
                        
                            //promote senary if populated
                            if ($this->senary_filter != ''){
                                $this->quinary_filter = $this->senary_filter;                
                                $this->quinary_filter_column = $this->senary_filter_column;
                                $this->quinary_filter_text = $this->senary_filter_text; 
                                $this->quinary_filter_column2 = $this->senary_filter_column2;
                                //clear senary filter
                                $this->senary_filter = '';                
                                $this->senary_filter_column = '';
                                $this->senary_filter_text = '';
                                $this->senary_filter_column2 = '';
                            }
                        } else {
                            $this->quaternary_filter = '';
                            $this->quaternary_filter_column = '';
                            $this->quaternary_filter_text = ''; 
                            $this->quaternary_filter_column2 = '';                   
                        }    
                    } else {
                        $this->tertiary_filter = '';
                        $this->tertiary_filter_column = '';
                        $this->tertiary_filter_text = '';
                        $this->tertiary_filter_column2 = '';                    
                    }                           
                } else {
                    $this->secondary_filter = '';
                    $this->secondary_filter_column = '';
                    $this->secondary_filter_text = '';     
                    $this->secondary_filter_column2 = '';               
                }               
            } elseif ($this->tertiary_filter == 'product'){
                //promote quaternary if populated
                if ($this->quarternary_filter != ''){
                    $this->tertiary_filter = $this->quarternary_filter;                
                    $this->tertiary_filter_column = $this->quarternary_filter_column;
                    $this->tertiary_filter_text = $this->quarternary_filter_text;    
                    $this->tertiary_filter_column2 = $this->quarternary_filter_column2;                                           
                
                    //promote quinary if populated
                    if ($this->quinary_filter != ''){
                        $this->quaternary_filter = $this->quinary_filter;                
                        $this->quaternary_filter_column = $this->quinary_filter_column;
                        $this->quaternary_filter_text = $this->quinary_filter_text;  
                        $this->quaternary_filter_column2 = $this->quinary_filter_column2;                                              
                    
                        //promote senary if populated
                        if ($this->senary_filter != ''){
                            $this->quinary_filter = $this->senary_filter;                
                            $this->quinary_filter_column = $this->senary_filter_column;
                            $this->quinary_filter_text = $this->senary_filter_text; 
                            $this->quinary_filter_column2 = $this->senary_filter_column2;
                            //clear senary filter
                            $this->senary_filter = '';                
                            $this->senary_filter_column = '';
                            $this->senary_filter_text = '';
                            $this->senary_filter_column2 = '';
                        }
                    } else {                        
                        $this->quaternary_filter = '';                
                        $this->quaternary_filter_column = '';
                        $this->quaternary_filter_text = '';
                        $this->quaternary_filter_column2 = '';
                        }                           
                } else {                
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
                    $this->tertiary_filter_column2 = '';
                }
            } elseif ($this->quaternary_filter == 'product'){                
                //promote quinary if populated
                if ($this->quinary_filter != ''){
                    $this->quaternary_filter = $this->quinary_filter;                
                    $this->quaternary_filter_column = $this->quinary_filter_column;                    
                    $this->quaternary_filter_text = $this->quinary_filter_text;    
                    $this->quaternary_filter_column2 = $this->quinary_filter_column2;                                            
                
                    //promote senary if populated
                    if ($this->senary_filter != ''){
                        $this->quinary_filter = $this->senary_filter;
                        $this->quinary_filter_column = $this->senary_filter_column;
                        $this->quinary_filter_text = $this->senary_filter_text;
                        $this->quinary_filter_column2 = $this->senary_filter_column2; 
                        //clear senary filter
                        $this->senary_filter = '';                
                        $this->senary_filter_column = '';
                        $this->senary_filter_text = '';
                        $this->senary_filter_column2 = '';                        
                    }
                } else {                
                    $this->quaternary_filter = '';                
                    $this->quaternary_filter_column = '';
                    $this->quaternary_filter_text = '';
                    $this->quaternary_filter_column2 = '';
                }
            } elseif ($this->quinary_filter == 'product'){                
                //promote senary if populated
                if ($this->senary_filter != ''){
                    $this->quinary_filter = $this->senary_filter;                
                    $this->quinary_filter_column = $this->senary_filter_column;
                    $this->quinary_filter_text = $this->senary_filter_text; 
                    $this->quinary_filter_column2 = $this->senary_filter_column2;
                    //clear senary filter
                    $this->senary_filter = '';                
                    $this->senary_filter_column = '';                    
                    $this->senary_filter_text = '';  
                    $this->senary_filter_column2 = '';                     
                } else {                
                    $this->quinary_filter = '';                
                    $this->quinary_filter_column = '';                    
                    $this->quinary_filter_text = '';
                    $this->quinary_filter_column2 = '';
                }
            } elseif ($this->senary_filter == 'product'){                                
                //clear senary filter
                $this->senary_filter = '';                
                $this->senary_filter_column = '';
                $this->senary_filter_text = '';
                $this->senary_filter_column2 = '';                
            }
        } 
        // dd($this->product_description_filter);       
    }

    public function buildSelects($dispatch_list){
        // dd($dispatch_list);
        $dispatch_report_jobcard_list = [];
        $dispatch_report_site_list = [];
        $dispatch_report_reference_list = [];
        $dispatch_report_customer_list = [];
        $dispatch_report_account_list = [];
        $dispatch_report_product_list = [];
        //Jobs & Sites
        foreach($dispatch_list as $item){            
            if($item->job_id != '0'){array_unshift($dispatch_report_jobcard_list, ['value' => $item->job_id, 'name' => $item->jobcard_number]);}
            if($item->job_id != '0'){array_unshift($dispatch_report_site_list, ['value' => $item->site_number, 'name' => $item->site_number]);}            
        }

        //Remove duplicate Jobs        
        $seenItems = array();
        foreach($dispatch_report_jobcard_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_jobcard_list[$index]);
            else
                $seenItems[] = $item["value"];
        } 
                             
        //Remove duplicate Sites
        $seenItems = array();
        foreach($dispatch_report_site_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_site_list[$index]);
            else
                $seenItems[] = $item["value"];
        }                
        
        //References
        foreach($dispatch_list as $item){
            array_unshift($dispatch_report_reference_list, ['value' => $item->reference, 'name' => $item->reference]);
        }

        //Remove duplicate References
        $seenItems = array();
        foreach($dispatch_report_reference_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_reference_list[$index]);
            else
                $seenItems[] = $item["value"];
        }

        //Customer & Accounts
        foreach($dispatch_list as $item){            
            if($item->customer_id != '0'){array_unshift($dispatch_report_customer_list, ['value' => $item->customer_id, 'name' => $item->name]);}
            if($item->customer_id != '0'){array_unshift($dispatch_report_account_list, ['value' => $item->account_number, 'name' => $item->account_number]);}            
        }

        //Remove duplicate Customers        
        $seenItems = array();
        foreach($dispatch_report_customer_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_customer_list[$index]);
            else
                $seenItems[] = $item["value"];
        } 
                             
        //Remove duplicate Accounts
        $seenItems = array();
        foreach($dispatch_report_account_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_account_list[$index]);
            else
                $seenItems[] = $item["value"];
        }

        //Products
        foreach($dispatch_list as $item){                       
            if($item->product_id != '0'){array_unshift($dispatch_report_product_list, ['value' => $item->product_id, 'name' => $item->description]);}          
        }
        //Add Linked Items from Transactions        
        foreach($dispatch_list as $item){                                               
            foreach($item->linked_transactions() as $linked_item) {
                if($linked_item->product_id != '0'){                
                    array_unshift($dispatch_report_product_list, ['value' => $linked_item->product_id, 'name' => $linked_item->product()->description]);
                }          
            }
        }

        //Remove duplicate Products        
        $seenItems = array();
        foreach($dispatch_report_product_list as $index => $item){
            if(in_array($item["value"], $seenItems))
                unset($dispatch_report_product_list[$index]);
            else
                $seenItems[] = $item["value"];
        }
        
        //return sorted lists
        // dd($dispatch_report_jobcard_list);
        $value  = array_column($dispatch_report_jobcard_list, 'value');
        $name = array_column($dispatch_report_jobcard_list, 'name');
        $this->dispatch_report_jobcard_list = $dispatch_report_jobcard_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_jobcard_list);        
        array_unshift($this->dispatch_report_jobcard_list, ['value' => '0', 'name' => 'All']);
                
        $value  = array_column($dispatch_report_site_list, 'value');
        $name = array_column($dispatch_report_site_list, 'name');
        $this->dispatch_report_site_list = $dispatch_report_site_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_site_list);
        array_unshift($this->dispatch_report_site_list, ['value' => '0', 'name' => 'All']);        
        
        $value  = array_column($dispatch_report_reference_list, 'value');
        $name = array_column($dispatch_report_reference_list, 'name');
        $this->dispatch_report_reference_list = $dispatch_report_reference_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_reference_list);
        array_unshift($this->dispatch_report_reference_list, ['value' => '0', 'name' => 'All']); 
        
        $value  = array_column($dispatch_report_customer_list, 'value');
        $name = array_column($dispatch_report_customer_list, 'name');
        $this->dispatch_report_customer_list = $dispatch_report_customer_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_customer_list);
        array_unshift($this->dispatch_report_customer_list, ['value' => '0', 'name' => 'All']);

        $value  = array_column($dispatch_report_account_list, 'value');
        $name = array_column($dispatch_report_account_list, 'name');
        $this->dispatch_report_account_list = $dispatch_report_account_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_account_list);
        array_unshift($this->dispatch_report_account_list, ['value' => '0', 'name' => 'All']);

        $value  = array_column($dispatch_report_product_list, 'value');
        $name = array_column($dispatch_report_product_list, 'name');
        $this->dispatch_report_product_list = $dispatch_report_product_list;
        array_multisort($name, SORT_ASC, $value, SORT_ASC, $this->dispatch_report_product_list);
        array_unshift($this->dispatch_report_product_list, ['value' => '0', 'name' => 'All']);
        
    }

    /* public function mount(){
        $this->extra_criteria = 0;
        $this->job_number_filter = 0;
        $this->site_number_filter = 0;
        $this->ref_number_filter = 0;
    } */

    function updatedExtraCriteria(){
        //Reset addtional filters on toggle
        if ($this->extra_criteria == false){            
            $this->resetAdditionals();
        }

    }
    
    public function render()
    {
        $dispatch_report_category_list = SelectLists::dispatch_report_categories;
        array_unshift($dispatch_report_category_list, ['value' => 0, 'name' => 'Select']);        
        
        $this->site_number_filter_old = $this->site_number_filter;
        $this->ref_number_filter_old = $this->ref_number_filter;
        $this->job_number_filter_old = $this->job_number_filter;
        $this->customer_name_filter_old = $this->customer_name_filter;
        $this->account_number_filter_old = $this->account_number_filter;
        $this->product_description_filter_old = $this->product_description_filter;

        if(isset($this->from_date) && isset($this->to_date) && $this->dispatch_report_category != 0){
            $this->extra_criteria_enabled = true;
        }

        if($this->extra_criteria == true){
            //there is now extra criteria enabled
            
            //Dispatches in Range
            if($this->dispatch_report_category == 'jobcard'){
                //filters - Contractors
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->join('manufacture_customers', 'manufacture_customers.id', '=', 'manufacture_jobcard_product_dispatches.customer_id', 'left outer')
                ->join('manufacture_products', 'manufacture_products.id', '=', 'manufacture_jobcard_product_dispatches.product_id', 'left outer')
                ->join('manufacture_product_transactions', 'manufacture_product_transactions.dispatch_id', '=', 'manufacture_jobcard_product_dispatches.id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.id as id','manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.customer_id as customer_id','manufacture_jobcard_product_dispatches.reference as reference'
                ,'manufacture_jobcard_product_dispatches.product_id as product_id','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number','manufacture_customers.name as name'
                ,'manufacture_customers.account_number as account_number','manufacture_products.description as description','manufacture_product_transactions.product_id as transactions_product_id')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')                        
                        ->orWhere($this->primary_filter_column2, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')
                                ->orWhere($this->secondary_filter_column2, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%')
                                        ->orWhere($this->tertiary_filter_column2, 'like', '%'.$this->tertiary_filter_text.'%')->where
                                        (function($query){
                                            if($this->quaternary_filter != ''){
                                                $query->where($this->quaternary_filter_column, 'like', '%'.$this->quaternary_filter_text.'%')
                                                ->orWhere($this->quaternary_filter_column2, 'like', '%'.$this->quaternary_filter_text.'%')->where
                                                (function($query){
                                                    if($this->quinary_filter != ''){
                                                        $query->where($this->quinary_filter_column, 'like', '%'.$this->quinary_filter_text.'%')
                                                        ->orWhere($this->quinary_filter_column2, 'like', '%'.$this->quinary_filter_text.'%')->where
                                                        (function($query){
                                                            if($this->senary_filter != ''){
                                                                $query->where($this->senary_filter_column, 'like', '%'.$this->senary_filter_text.'%')
                                                                ->orWhere($this->senary_filter_column2, 'like', '%'.$this->senary_filter_text.'%');
                                                            }
                                                        });
                                                    }                                                    
                                                });
                                            }                                            
                                        });
                                    }                                    
                                });
                            }
                        });
                    }
                })
                ->where('manufacture_jobcard_product_dispatches.job_id','!=', '0')                              
                // ->with('linked_transactions')
                ->get();                               
                
            } elseif($this->dispatch_report_category == 'cash'){
                //filters - Cash               
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->join('manufacture_customers', 'manufacture_customers.id', '=', 'manufacture_jobcard_product_dispatches.customer_id', 'left outer')
                ->join('manufacture_products', 'manufacture_products.id', '=', 'manufacture_jobcard_product_dispatches.product_id', 'left outer')
                ->join('manufacture_product_transactions', 'manufacture_product_transactions.dispatch_id', '=', 'manufacture_jobcard_product_dispatches.id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.id as id','manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.customer_id as customer_id','manufacture_jobcard_product_dispatches.reference as reference'
                ,'manufacture_jobcard_product_dispatches.product_id as product_id','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number','manufacture_customers.name as name'
                ,'manufacture_customers.account_number as account_number','manufacture_products.description as description','manufacture_product_transactions.product_id as transactions_product_id')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')                
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')
                        ->orWhere($this->primary_filter_column2, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')
                                ->orWhere($this->secondary_filter_column2, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%')
                                        ->orWhere($this->tertiary_filter_column2, 'like', '%'.$this->tertiary_filter_text.'%')->where
                                        (function($query){
                                            if($this->quaternary_filter != ''){
                                                $query->where($this->quaternary_filter_column, 'like', '%'.$this->quaternary_filter_text.'%')
                                                ->orWhere($this->quaternary_filter_column2, 'like', '%'.$this->quaternary_filter_text.'%')->where
                                                (function($query){
                                                    if($this->quinary_filter != ''){
                                                        $query->where($this->quinary_filter_column, 'like', '%'.$this->quinary_filter_text.'%')
                                                        ->orWhere($this->quinary_filter_column2, 'like', '%'.$this->quinary_filter_text.'%')->where
                                                        (function($query){
                                                            if($this->senary_filter != ''){
                                                                $query->where($this->senary_filter_column, 'like', '%'.$this->senary_filter_text.'%')
                                                                ->orWhere($this->senary_filter_column2, 'like', '%'.$this->senary_filter_text.'%');
                                                            }
                                                        });
                                                    }                                                    
                                                });
                                            }                                            
                                        });
                                    }                                    
                                });
                            }
                        });
                    }
                })
                ->where('manufacture_jobcard_product_dispatches.customer_id','!=', '0')
                // ->with('linked_transactions')                                                    
                ->get();
                
            } else {
                //filters - All - Will add Cash Clients first then add Jobs during loop below - GroupBy Limitiations on dual fields
                $dispatch_list = ManufactureJobcardProductDispatches::join('manufacture_jobcards', 'manufacture_jobcards.id', '=', 'manufacture_jobcard_product_dispatches.job_id', 'left outer')
                ->join('manufacture_customers', 'manufacture_customers.id', '=', 'manufacture_jobcard_product_dispatches.customer_id', 'left outer')
                ->join('manufacture_products', 'manufacture_products.id', '=', 'manufacture_jobcard_product_dispatches.product_id', 'left outer')
                ->join('manufacture_product_transactions', 'manufacture_product_transactions.dispatch_id', '=', 'manufacture_jobcard_product_dispatches.id', 'left outer')
                ->select('manufacture_jobcard_product_dispatches.id as id','manufacture_jobcard_product_dispatches.job_id as job_id','manufacture_jobcard_product_dispatches.customer_id as customer_id','manufacture_jobcard_product_dispatches.reference as reference'
                ,'manufacture_jobcard_product_dispatches.product_id as product_id','manufacture_jobcards.jobcard_number as jobcard_number','manufacture_jobcards.site_number as site_number','manufacture_customers.name as name'
                ,'manufacture_customers.account_number as account_number','manufacture_products.description as description','manufacture_product_transactions.product_id as transactions_product_id')
                ->where('manufacture_jobcard_product_dispatches.status', 'Dispatched')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '>=', $this->from_date.' 00:00:01')
                ->where('manufacture_jobcard_product_dispatches.weight_out_datetime', '<=', $this->to_date.' 23:59:59')
                ->where (function($query){
                    if($this->primary_filter != ''){
                        // dd($this->primary_filter.' '.$this->primary_filter_column.' '.$this->primary_filter_text);
                        $query->where($this->primary_filter_column, 'like', '%'.$this->primary_filter_text.'%')
                        ->orWhere($this->primary_filter_column2, 'like', '%'.$this->primary_filter_text.'%')->where
                        (function($query){
                            if($this->secondary_filter != ''){
                                $query->where($this->secondary_filter_column, 'like', '%'.$this->secondary_filter_text.'%')
                                ->orWhere($this->secondary_filter_column2, 'like', '%'.$this->secondary_filter_text.'%')->where
                                (function($query){
                                    if($this->tertiary_filter != ''){
                                        $query->where($this->tertiary_filter_column, 'like', '%'.$this->tertiary_filter_text.'%')
                                        ->orWhere($this->tertiary_filter_column2, 'like', '%'.$this->tertiary_filter_text.'%')->where
                                        (function($query){
                                            if($this->quaternary_filter != ''){
                                                $query->where($this->quaternary_filter_column, 'like', '%'.$this->quaternary_filter_text.'%')
                                                ->orWhere($this->quaternary_filter_column2, 'like', '%'.$this->quaternary_filter_text.'%')->where
                                                (function($query){
                                                    if($this->quinary_filter != ''){
                                                        $query->where($this->quinary_filter_column, 'like', '%'.$this->quinary_filter_text.'%')
                                                        ->orWhere($this->quinary_filter_column2, 'like', '%'.$this->quinary_filter_text.'%')->where
                                                        (function($query){
                                                            if($this->senary_filter != ''){
                                                                $query->where($this->senary_filter_column, 'like', '%'.$this->senary_filter_text.'%')
                                                                ->orWhere($this->senary_filter_column2, 'like', '%'.$this->senary_filter_text.'%');
                                                            }
                                                        });
                                                    }                                                    
                                                });
                                            }                                            
                                        });
                                    }                                    
                                });
                            }
                        });
                    }
                })  
                // ->with('linked_transactions')                                              
                ->get();
                /* $query = str_replace(array('?'), array('\'%s\''), $dispatch_list->toSql());
                $query = vsprintf($query, $dispatch_list->getBindings());
                dd($query); */                
                
            } 
            // dd($dispatch_list);
            $this->buildSelects($dispatch_list);
            
            
        }

        

        return view('livewire.manufacture.reports.dispatch-livewire', ['dispatch_report_category_list' => $dispatch_report_category_list]);
    }
}





//old filter code before adding columns
/* function updatedJobNumberFilter(){        
    if($this->job_number_filter != '0'){
        //New value is not All
        if($this->job_number_filter_old != '0' && $this->job_number_filter_old != $this->job_number_filter){
            if($this->primary_filter == 'job'){                    
                $this->primary_filter_text = $this->job_number_filter;
            } elseif ($this->secondary_filter == 'job'){                    
                $this->secondary_filter_text = $this->job_number_filter;
            } elseif ($this->tertiary_filter == 'job'){                   
                $this->tertiary_filter_text = $this->job_number_filter;
            }
        } else {
            if($this->primary_filter == ''){
                $this->primary_filter = 'job';
                $this->primary_filter_column = 'job_id';
                $this->primary_filter_text = $this->job_number_filter;
            } elseif ($this->secondary_filter == ''){
                $this->secondary_filter = 'job';
                $this->secondary_filter_column = 'job_id';
                $this->secondary_filter_text = $this->job_number_filter;
            } elseif ($this->tertiary_filter == ''){
                $this->tertiary_filter = 'job';                
                $this->tertiary_filter_column = 'job_id';
                $this->tertiary_filter_text = $this->job_number_filter;
            }
        }
    } else {
        if($this->primary_filter == 'job'){
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
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';                       
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
                            
        } elseif ($this->secondary_filter == 'job'){                
            //promote tertiary if populated
            if ($this->tertiary_filter != ''){
                $this->secondary_filter = $this->tertiary_filter;                
                $this->secondary_filter_column = $this->tertiary_filter_column;
                $this->secondary_filter_text = $this->tertiary_filter_text;
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            } else {
                $this->secondary_filter = '';
                $this->secondary_filter_column = '';
                $this->secondary_filter_text = '';                    
            }               
        } elseif ($this->tertiary_filter == 'job'){
            //clear tertiary filter
            $this->tertiary_filter = '';                
            $this->tertiary_filter_column = '';
            $this->tertiary_filter_text = '';
        }
    }               
}

function updatedSiteNumberFilter(){
    if($this->site_number_filter != '0'){
        //New value is not All
        if($this->site_number_filter_old != '0' && $this->site_number_filter_old != $this->site_number_filter){
            if($this->primary_filter == 'site'){                    
                $this->primary_filter_text = $this->site_number_filter;
            } elseif ($this->secondary_filter == 'site'){                    
                $this->secondary_filter_text = $this->site_number_filter;
            } elseif ($this->tertiary_filter == 'site'){                   
                $this->tertiary_filter_text = $this->site_number_filter;
            }
        } else {
            if($this->primary_filter == ''){
                $this->primary_filter = 'site';
                $this->primary_filter_column = 'site_number';
                $this->primary_filter_text = $this->site_number_filter;
            } elseif ($this->secondary_filter == ''){
                $this->secondary_filter = 'site';
                $this->secondary_filter_column = 'site_number';
                $this->secondary_filter_text = $this->site_number_filter;
            } elseif ($this->tertiary_filter == ''){
                $this->tertiary_filter = 'site';                
                $this->tertiary_filter_column = 'site_number';
                $this->tertiary_filter_text = $this->site_number_filter;
            }
        }
    } else {
        if($this->primary_filter == 'site'){
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
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
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
                            
        } elseif ($this->secondary_filter == 'site'){                
            //promote tertiary if populated
            if ($this->tertiary_filter != ''){
                $this->secondary_filter = $this->tertiary_filter;                
                $this->secondary_filter_column = $this->tertiary_filter_column;
                $this->secondary_filter_text = $this->tertiary_filter_text;
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
            } else {
                $this->secondary_filter = '';
                $this->secondary_filter_column = '';
                $this->secondary_filter_text = '';
            }                
        } elseif ($this->tertiary_filter == 'site'){
            //clear tertiary filter
            $this->tertiary_filter = '';                
            $this->tertiary_filter_column = '';
            $this->tertiary_filter_text = '';
        }
    }            
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
                $this->primary_filter_column = 'reference';
                $this->primary_filter_text = $this->ref_number_filter;
            } elseif ($this->secondary_filter == ''){
                $this->secondary_filter = 'ref';
                $this->secondary_filter_column = 'reference';
                $this->secondary_filter_text = $this->ref_number_filter;
            } elseif ($this->tertiary_filter == ''){
                $this->tertiary_filter = 'ref';                
                $this->tertiary_filter_column = 'reference';
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
                    //clear tertiary filter
                    $this->tertiary_filter = '';                
                    $this->tertiary_filter_column = '';
                    $this->tertiary_filter_text = '';
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
                //clear tertiary filter
                $this->tertiary_filter = '';                
                $this->tertiary_filter_column = '';
                $this->tertiary_filter_text = '';
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
} */