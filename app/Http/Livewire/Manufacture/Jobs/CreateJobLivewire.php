<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use Livewire\Component;

class CreateJobLivewire extends Component
{
    public $jobcard_number,
        $contractor,
        $site_number,
        $contact_person,
        $delivery,
        $delivery_address,
        $notes;

    public function render()
    {
        return view('livewire.manufacture.jobs.create-job-livewire');
    }
}
