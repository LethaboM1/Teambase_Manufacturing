<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use App\Http\Controllers\Functions;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureCustomers;
use App\Models\ManufactureJobcards;

class CreateJobLivewire extends Component
{
    public $jobcard_number,
        $contractor,
        $site_number,
        $contact_person,
        $delivery,
        $delivery_address,
        $notes,
        $internal_jobcard,
        $site_number_new;        
    // $customer_id,
    // $customer_contact,
    // $customer_address; Only Internal Jobs 2023-10-19

    function mount()
    {
        $this->internal_jobcard = 1;
        $this->delivery = 1;
    }

    /* function updatedCustomerId($value)
    {
        $customer = ManufactureCustomers::where('id', $value)->first();
        $this->contact_person = $customer['contact_name'];
        $this->delivery_address = $customer['address'];        
    } Only internal Jobs 2023-10-19 */

    function updatedDelivery($value)
    {
        $this->delivery = $value;
       //dd($this->delivery);
    }

    /* function incrementSiteSubNo($number){
        // get amount of decimals
        $decimal = strlen(strrchr($number, '.')) -1;
      
        $factor = pow(10,$decimal);
      
        $incremented = (($factor * $number) + 1) / $factor;
      
        return $incremented;
    } */

    function updatedSiteNumber()
    {
        //Validation on Site Number Formatting     
        // $current_sites = ManufactureJobcards::select('site_number')->distinct()->get();
        // $current_sites = ManufactureJobcards::select('site_number', \DB::raw('substr(site_number, 1, 4) as siteno, concat(".",substr(site_number, 6)) as subno'))->distinct()->get();        
        
        // if($current_sites->where('siteno', substr($this->site_number, 0, 4))->count() > 0){
        //     // dd('we have a double');                        
        //     $siteno=$current_sites->where('siteno', substr($this->site_number, 0, 4))->first()->siteno;
        //     $subno=$current_sites->where('siteno', substr($this->site_number, 0, 4))->sortByDesc('subno')->first()->subno;
            
        //     $subnonext= Functions::incrementSiteSubNo($subno);
        //     $siteno=floatval($siteno+$subnonext);
            
        //     $this->site_number = str_replace('.','/',$siteno);           

        //     // dd('current highest sub:'.$subno.', next no:'.$subnonext);            

        // } else{
        //     // dd('we do not have a double');
        //     $siteno=substr($this->site_number, 0, 4);
        //     $subno='.'.substr($this->site_number, 5);
            
        //     if($subno == '.00'){
        //         $subnonext=Functions::incrementSiteSubNo($subno);
        //         $siteno=floatval($siteno+$subnonext);
        //         $this->site_number = str_replace('.','/',$siteno);
        //     } else {
        //         $subnonext = $subno;
        //         $siteno=floatval($siteno+$subnonext);
        //         $this->site_number = str_replace('.','/',$siteno);
        //     }
            
        //     // dd('current highest sub:'.$subno.', next no:'.$subnonext);
        // } Site Number checks removed 2024-03-18

        $siteno=substr($this->site_number, 0, 7);
        $this->site_number = str_replace('.','/',$siteno);
        
        $this->site_number_new=$this->site_number;
    }

    public function render()
    {
        /* $customer_list = [];
        $customer_list = ManufactureCustomers::select('id as value', DB::raw("concat(account_number,' - ',name) as name"))
            ->get()
            ->toArray();


        if (count($customer_list) > 0) {

            array_unshift($customer_list, ['value' => 0, 'name' => 'Select Customer']);
        } else {
            $customer_list = [];
            array_unshift($jobcard_list, ['value' => 0, 'name' => 'No Customers found...']);
        }  Only Internal Jobs 2023-10-19 */      

        return view('livewire.manufacture.jobs.create-job-livewire', [
            'internal_jobcard' => $this->internal_jobcard,
            // 'customer_contact' => $this->customer_contact,
            // 'customer_address' => $this->customer_address,
            //'customer_list' => $customer_list Only Internal Jobs 2023-10-19
        ]);
    }
}
