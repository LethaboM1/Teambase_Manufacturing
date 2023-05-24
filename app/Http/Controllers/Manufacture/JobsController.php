<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    function jobs()
    {
        return view('manufacture.jobs.list');
    }

    function create_job()
    {
        return view('manufacture.jobs.create-job');
    }
}
