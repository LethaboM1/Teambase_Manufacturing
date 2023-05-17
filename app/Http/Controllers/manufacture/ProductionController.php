<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    function productions()
    {
        return view('manufacture.production.open-batches');
    }

    function create_batch()
    {
        return view('manufacture.production.create-batch');
    }
}
