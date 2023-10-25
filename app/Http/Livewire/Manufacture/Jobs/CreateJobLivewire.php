<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ManufactureCustomers;

class CreateJobLivewire extends Component
{
    public $jobcard_number,
        $contractor,
        $site_number,
        $contact_person,
        $delivery,
        $delivery_address,
        $notes,
        $internal_jobcard;        
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
