<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions;
use App\Models\ManufactureCustomers;
use App\Models\ManufactureJobcards;
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

    function view_job($job)
    {
        return view('manufacture.jobs.view-job', [
            'job' => $job
        ]);
    }

    function add_job(Request $request)
    {

        $form_fields = $request->validate([
            'internal_jobcard' => 'nullable',
            'customer_id' => 'nullable',
            'contractor' => 'nullable',
            'site_number' => 'nullable',
            'contact_person' => 'nullable',
            'delivery_address' => 'nullable',
            'notes' => 'nullable',
            'delivery' => 'nullable',
        ]);



        if (!isset($form_fields['internal_jobcard'])) $form_fields['internal_jobcard'] = 0;
        if (!isset($form_fields['customer_id'])) $form_fields['customer_id'] = 0;

        //Check for valid Customer
        if ($form_fields['internal_jobcard'] == 0 && $form_fields['customer_id'] == 0) return back()->with('alertError', 'Please select a Customer for this External Jobcard.');
        //Check for Address
        if ($form_fields['delivery'] && strlen($form_fields['delivery_address']) == 0) return back()->with('alertError', 'Please type in a delivery address if delivery is required');

        if ($form_fields['internal_jobcard'] == 0 && $form_fields['customer_id'] > 0) {
            $customer = ManufactureCustomers::where('id', $form_fields['customer_id'])->first();
            $form_fields['contractor'] = $customer['name'];
        }

        $form_fields['status'] = 'Open';
        $form_fields['jobcard_number'] = Functions::get_doc_number('jobcard');

        $job_id = ManufactureJobcards::insertGetId($form_fields);
        return redirect("job/{$job_id}");
    }
}
