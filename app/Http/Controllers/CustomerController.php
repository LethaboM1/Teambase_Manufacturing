<?php

namespace App\Http\Controllers;

use App\Models\ManufactureCustomers;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function customers()
    {
        return view('manufacture.customers.view');
    }

    function add_customer(Request $request)
    {
        $form_fields = $request->validate([
            'name' => 'required|unique:manufacture_customers',
            'credit' => 'nullable',
            'account_number' => '',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'vat_number' => 'nullable',
            'address' => 'nullable',
        ]);

        if (!isset($form_fields['credit'])) $form_fields['credit'] = 0;
        if ($form_fields['credit'] == 0) {
            $form_fields['account_number'] = '';
        }

        ManufactureCustomers::insert($form_fields);

        return back()->with('alertMessage', "Customer \"{$form_fields['name']}\" added!");
    }

    function save_customer(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'required|exists:manufacture_customers',
            'name' => "required|unique:manufacture_customers,id,{$request->id}",
            'credit' => 'nullable',
            'account_number' => '',
            'contact_name' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'vat_number' => 'nullable',
            'address' => 'nullable',
        ]);

        if (!isset($form_fields['credit'])) $form_fields['credit'] = 0;
        if ($form_fields['credit'] == 0) {
            $form_fields['account_number'] = '';
        }

        ManufactureCustomers::where('id', $form_fields['id'])->update($form_fields);

        return back()->with('alertMessage', "Customer \"{$form_fields['name']}\" saved!");
    }

    function delete_customer(Request $request)
    {
    }
}
