<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabsController extends Controller
{
    function create_lab()
    {
        return view('manufacture.lab.create');
    }
}
