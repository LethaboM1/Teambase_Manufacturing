<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductsTransactions;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    function products()
    {
        return view('manufacture.products.list');
    }

    function add_product(Request $request)
    {
        $form_fields = $request->validate([
            'code' => 'required|unique:manufacture_products',
            'description' => 'required|unique:manufacture_products',
            'opening_balance' => 'nullable',
            'unit_measure' => 'required',
            'has_recipe' => 'nullable',
        ]);

        $form_fields['has_recipe'] = (isset($form_fields['has_recipe']) ? 1 : 0);

        $opening_balance = (is_numeric($form_fields['opening_balance']) && $form_fields['opening_balance'] > 0) ?  $form_fields['opening_balance'] : 0;

        unset($form_fields['opening_balance']);

        $product_id = ManufactureProducts::insertGetId($form_fields);

        if ($opening_balance) {
            ManufactureProductsTransactions::insert([
                'product_id' => $product_id,
                'type' => 'OB',
                'qty' => $opening_balance,
                'comment' => 'Opening balance'
            ]);
        }

        return back()->with('alertMessage', 'Product has been added');
    }


    function save_product(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'exists:manufacture_products',
            'code' => "required|unique:manufacture_products,code,{$request->id}",
            'description' => "required|unique:manufacture_products,description,{$request->id}",
            'unit_measure' => 'required',
            'has_recipe' => 'nullable',
        ]);

        $form_fields['has_recipe'] = (isset($form_fields['has_recipe']) ? 1 : 0);


        ManufactureProducts::where('id', $form_fields['id'])->update($form_fields);

        return back()->with('alertMessage', 'Product has been saved');
    }
}
