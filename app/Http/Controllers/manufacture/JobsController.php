<?php

namespace App\Http\Controllers\Manufacture;

use App\Http\Controllers\Controller;
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
            'jobcard_number' => 'required',
            'contractor' => 'nullable',
            'site_number' => 'nullable',
            'contact_person' => 'nullable',
            'delivery_address' => 'nullable',
            'notes' => 'nullable',
            'delivery' => 'nullable',
        ]);

        // dd($form_fields);
        if ($form_fields['delivery'] && strlen($form_fields['delivery_address']) == 0) return back()->with('alertMessage', 'Please type in a delivery address if delivery is required');
        $form_fields['status'] = 'Open';

        $job_id = ManufactureJobcards::insertGetId($form_fields);
        return redirect("job/{$job_id}");
    }
}
