<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

    public function profile(){        
        return view('user-profile');
    }
    
    function outofoffice_user(Request $request)
    {
        $form_fields = $request->validate([
            'user_id' => 'required|exists:users_tbl,user_id',
            'out_of_office' => 'nullable'
        ]);       

        if($form_fields['out_of_office'] == 'true'){
            $form_fields['out_of_office'] = '0';
        } else {
            $form_fields['out_of_office'] = '1';
        }
        
        User::where('user_id', $form_fields['user_id'])->update($form_fields);
        
        $user = User::where('user_id', $form_fields['user_id'])->first();        
        $status = $user->out_of_office == '1' ? "Out Of Office":"Back at Office";

        return back()->with('alertMessage', "User {$user->name} {$user->last_name} has been set to ".$status);
    }
}
