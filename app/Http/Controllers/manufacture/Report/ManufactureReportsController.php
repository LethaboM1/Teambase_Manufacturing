<?php

namespace App\Http\Controllers\Manufacture\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufactureReportsController extends Controller
{
    function report_stock()
    {
        return view('manufacture.report.report_stock');
    }
    function report_order()
    {
        return view('manufacture.report.report_order');
    }
    function report_lab()
    {
        return view('manufacture.report.report_lab');
    }
    function report_dispatch()
    {
        return view('manufacture.report.report_dispatch');
    }
}
