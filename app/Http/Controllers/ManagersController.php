<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Functions;
use App\Models\Settings as ModelsSettings;

class ManagersController extends Controller
{
    function  users()
    {
        return view('managers.users');
    }

    function add_user(Request $request)
    {
        $form_fields = $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'employee_number' => 'nullable',
            'id_number' => 'nullable',
            'company_number' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'username' => 'required|unique:users_tbl,username',
            'password' => 'required|confirmed',
            'role' => 'required',
            'active' => 'nullable',
        ]);

        if (!Functions::validPassword($form_fields['password'])) return back()->with('alertError', 'Password is invalid, must be uppercase, lowercase, numbers, special chars and 8 or more digits.');
        $form_fields['password'] = password_hash($form_fields['password'], PASSWORD_DEFAULT);
        $form_fields['depart'] = auth()->user()->depart;

        User::insert($form_fields);

        return back()->with([
            'alertMessage' => 'User added!'
        ]);
    }

    function save_user(Request $request)
    {
        $form_fields = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'employee_number' => 'nullable',
            'id_number' => 'nullable',
            'company_number' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable',
            'username' => "required|unique:users_tbl,username,{$request->user_id},user_id",
            'role' => 'required',
            'active' => 'nullable'
        ]);

        if (isset($request->password) && strlen($request->password) > 0) {
            if (!Functions::validPassword($request->password)) return back()->with('alertError', 'Password is invalid, must be uppercase, lowercase, numbers, special chars and 8 or more digits.');
            if ($request->password != $request->password_confirmation)  return back()->with('alertError', 'Passwords dont match.');
            $form_fields['password'] = password_hash($request->password, PASSWORD_DEFAULT);
        }

        $form_fields['depart'] = auth()->user()->depart;
        User::where('user_id', $form_fields['user_id'])->update($form_fields);

        return back()->with([
            'alertMessage' => 'User saved!'
        ]);
    }

    function outofoffice_user(Request $request)
    {
        $form_fields = $request->validate([
            'user_id' => 'required|exists:users_tbl,user_id',
            'out_of_office' => 'nullable'
        ]);

        $form_fields['out_of_office'] = (!isset($form_fields['out_of_office']) ? 0 : 1);
        User::where('user_id', $form_fields['user_id'])->update($form_fields);
        $user = User::where('user_id', $form_fields['user_id'])->first();
        return back()->with('alertMessage', "User {$user->name} {$user->last_name} has been set.");
    }

    function delete_user(Request $request)
    {
        $form_fields = $request->validate([
            'user_id' => 'required|exists:users_tbl,user_id'
        ]);

        User::where('user_id', $form_fields['user_id'])->update(['active' => 0]);
        return back()->with('alertMessage', 'User has been deleted!');
    }

    function  setting()
    {
        return view('managers.settings');
    }

    function save_settings(Request $request)
    {
        
        $form_fields = $request->validate([
            'settings_rows' => 'nullable',
            'trade_name' => 'required',
            'reg_no' => 'nullable',
            'vat_no' => 'nullable',
            'tel_no' => 'required',
            'fax_no' => 'nullable',
            'mobile' => 'nullable',
            'email' => 'required',
            'url' => 'nullable',
            'logo' => 'nullable',
            'physical_add' => 'nullable',
            'postal_add' => 'nullable'            
        ]);
        
        $form_fields['logo'] = $request['company_logo'];        
        
        if($form_fields['settings_rows'] > 0){
            //Update
            unset($form_fields['settings_rows']);
            ModelsSettings::where('trade_name', 'like', '%')->update($form_fields);
        } else {
            //Insert
            unset($form_fields['settings_rows']);
            ModelsSettings::insert($form_fields);
        }
        return back()->with([
            'alertMessage' => 'Settings saved!'
        ]);
    }
}
