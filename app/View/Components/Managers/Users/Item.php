<?php

namespace App\View\Components\Managers\Users;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class Item extends Component
{
    public $item, $sec_levels, $crud_items=[];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
        // dd($item->getSec()); 
              
        
        if($item->getSec()!=null){            
            // dd($item->getSec());
            $this->sec_levels = $item->getSec()->toArray();
            
            //Walk through Sec Level Array and add C.R.U.D keys under each sec_level_crud item
            $this->getCRUD($this->sec_levels);
            
        } else {
            //Create Default CRUD List
            $array = DB::getSchemaBuilder()->getColumnListing('user_sec_tbl');            
            $return_array =[];                     
            
            foreach ($array as $sec_level => $value){                
                if(str_contains($value, 'crud')){
                    //CRUD Sec Item                                
                    $return_array[$value] = [
                    '0' => '0000',
                    'create' => false,
                    'read' => false,
                    'update' => false,
                    'delete' => false];
                    
                    $this->crud_items[$value]='0000';
                } else if ($value=='id' || $value=='user_id' || str_contains($value, 'ted_at')){
                    unset($array[$sec_level]);
                } else {
                    $return_array[$value]='0';
                }
            }
            
            $this->sec_levels=$return_array;
            
        }
        // array_push($this->sec_levels, $this->item);
        // dd($this->sec_levels);
        
    }

    public function getCRUD($array=['']){
        
        foreach ($array as $sec_level => $value){
            
            if(str_contains($sec_level, 'crud')){
                //CRUD Sec Item
                $sec = $value;
                $array[$sec_level] = [$sec,
                'create' => substr($sec,0,1) == '1' ? true:false,
                'read' => substr($sec,1,1) == '1' ? true:false,
                'update' => substr($sec,2,1) == '1' ? true:false,
                'delete' => substr($sec,3,1) == '1' ? true:false];
                
                $this->crud_items[$sec_level] = $value;
            }
        }

        $this->sec_levels=$array;        
        
    }

    // public function updateSec(){
    //     if(substr(Auth::user()->getSec()->user_man_crud,2,1)=='1'){ 
            
    //         $form_fields=[];
            
    //         foreach($this->sec_levels as $sec_level => $value){
    //             if( (!str_contains($sec_level, '_crud')) && (!str_contains($sec_level, 'id')) && (!str_contains($sec_level, 'ted_at')) ){
    //                 //Exclude CRUD rows and reserved columns                                    
    //                 $form_fields[$sec_level] = $value == '1' ? '1':($value == 'true' ? '1':'0');

    //             } elseif (str_contains($sec_level, '_crud')) {
    //                 //Process CRUD items                                        
    //                 $combined_value='';
    //                 foreach($value as $sub => $sub_value){                        
    //                     if($sub != 0){
    //                         $combined_value = $combined_value . ($sub_value == '1' ? '1':($sub_value == 'true' ? '1':'0'));
    //                     }                                                                        
    //                 }
    //                 $form_fields[$sec_level] = $combined_value;

    //             }
    //         }

    //         //Update User Sec Table
    //         UsersSec::where('user_id', $this->userprofile->id)->update($form_fields);
            
    //         return redirect('/userman')->with(['alertMessage'=>'Posted changes to User Security Levels for '.$this->userprofile->name.'!']);            

    //     }
    // }



    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.managers.users.item');
    }
}
