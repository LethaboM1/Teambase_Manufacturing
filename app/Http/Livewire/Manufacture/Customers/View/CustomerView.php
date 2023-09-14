<?php

namespace App\Http\Livewire\Manufacture\Customers\View;

use Livewire\Component;
use App\Models\ManufactureCustomers;

class CustomerView extends Component
{
    public $customer, $customer_id, $credit_set;
    function mount($customerid = 0)
    {
        $this->customer_id = $customerid;

        if ($this->customer_id > 0) {
            $this->customer = ManufactureCustomers::where('id', $this->customer_id)->first();
            $this->credit_set = $this->customer['credit'];
        } else {
            $this->customer = [
                'name' => '',
                'credit' => 0,
                'account_number' => '',
                'contact_name' => '',
                'contact_number' => '',
                'email' => '',
                'vat_number' => '',
                'address' => '',
            ];
            $this->credit_set = 0;
        }
    }

    public function render()
    {
        return view('livewire.manufacture.customers.view.customer-view');
    }
}
