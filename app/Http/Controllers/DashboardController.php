<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function view () {
        $page_title = "Dashboard";
        return view('dashboard',[
            'page_title'=>$page_title
        ]);
    }
}
