<?php

namespace App\View\Components\Managers\Users;

use App\Models\UserSec;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DefaultsController;

class View extends Component
{
    public $user, $sec_levels, $roles_list, $crud_items=[];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $cruditems, $seclevels)
    {
        
        if ($user == null) {
            $this->user = [
                'name' => '',
                'last_name' => '',
                'employee_number' => '',
                'id_number' => '',
                'employee_number' => '',
                'company_number' => '',
                'contact_number' => '',
                'email' => '',
                'username' => '',
                'password' => '',
                'role' => '',
                'active' => 1,
                'out_of_office' => 0,
            ];           

        } else {
            $this->user = $user;          
                       
        } 
        
        if ($cruditems == null) {
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

        } elseif ($seclevels != null) {            
            
            //Get SecLevels from Items Sent from upstream
            $this->sec_levels = $seclevels;
            //Get CRUD from Items Sent from upstream
            $this->crud_items = $cruditems;
            // dd($this->sec_levels);            

        }

        $this->roles_list = DefaultsController::roles[auth()->user()->depart];
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $sec_array = base64_encode(json_encode($this->sec_levels));       
        
        return view('components.managers.users.view', ['sec_array' => $sec_array]);
    }
}
