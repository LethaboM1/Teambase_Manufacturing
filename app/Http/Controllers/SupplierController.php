<?php

namespace App\Http\Controllers;

use App\Models\ManufactureSuppliers;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    function suppliers()
    {
        return view('manufacture.suppliers.view');
    }

    function add_supplier(Request $request)
    {
        $form_fields = $request->validate([
            'name' => 'required|unique:manufacture_suppliers',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'vat_number' => 'nullable',
            'address' => 'nullable',
        ]);

        ManufactureSuppliers::insert($form_fields);

        return back()->with('alertMessage', "Supplier \"{$form_fields['name']}\" added!");
    }

    function save_supplier(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'required|exists:manufacture_suppliers',
            'name' => "required|unique:manufacture_suppliers,id,{$request->id}",
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'vat_number' => 'nullable',
            'address' => 'nullable',
        ]);

        ManufactureSuppliers::where('id', $form_fields['id'])->update($form_fields);

        return back()->with('alertMessage', "Supplier \"{$form_fields['name']}\" saved!");
    }

    function delete_supplier(Request $request)
    {
    }
}
