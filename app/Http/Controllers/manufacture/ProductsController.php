<?php

namespace App\Http\Controllers\manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufactureProductRecipe;
use App\Models\ManufactureProducts;
use App\Models\ManufactureProductTransactions;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function adjust_product(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'required|exists:manufacture_products',
            'new_value' => 'required|numeric',
            'comment' => 'required'
        ]);

        $qty = ManufactureProducts::where('id', $form_fields['id'])->first();

        $diff = $form_fields['new_value'] - $qty->qty;


        ManufactureProductTransactions::insert([
            'product_id' => $form_fields['id'],
            'type' => 'ADJ',
            'qty' => $diff,
            'comment' => $form_fields['comment'],
            'user_id' => auth()->user()->user_id

        ]);

        return back()->with('alertMessage', 'Product qty has been adjusted.');
    }

    public function products()
    {
        return view('manufacture.products.list');
    }

    public function add_product(Request $request)
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
            ManufactureProductTransactions::insert([
                'product_id' => $product_id,
                'type' => 'OB',
                'qty' => $opening_balance,
                'comment' => 'Opening balance',
                'user_id' => auth()->user()->user_id
            ]);
        }

        return back()->with('alertMessage', 'Product has been added');
    }


    public function save_product(Request $request)
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

    public function delete_product(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'required|exists:manufacture_products'
        ]);

        ManufactureProductRecipe::select('id')->where('product_add_id', $form_fields['id'])->delete();
        ManufactureProductTransactions::select('id')->where('product_id', $form_fields['id'])->delete();
        ManufactureProducts::select('id')->where('product_add_id', $form_fields['id'])->delete();



        return back()->with('alertMessage', 'Product has been removed.');
    }
}
