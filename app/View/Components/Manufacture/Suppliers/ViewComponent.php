<?php

namespace App\View\Components\Manufacture\Suppliers;

use App\Models\ManufactureSuppliers;
use Illuminate\View\Component;

class ViewComponent extends Component
{
    public $supplier, $supplier_id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($supplierid = 0)
    {
        $this->supplier_id = $supplierid;

        if ($this->supplier_id > 0) {
            $this->supplier = ManufactureSuppliers::where('id', $this->supplier_id)->first();
        } else {
            $this->supplier = [
                'name' => '',
                'contact_name' => '',
                'contact_number' => '',
                'email' => '',
                'vat_number' => '',
                'address' => '',
            ];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.manufacture.suppliers.view-component');
    }
}
