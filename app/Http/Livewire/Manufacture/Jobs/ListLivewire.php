<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use App\Models\ManufactureJobcards;
use Livewire\Component;
use Livewire\WithPagination;

class ListLivewire extends Component
{
    use WithPagination;
    public function render()
    {
        $jobcards_list = ManufactureJobcards::where('status', '!=', 'Completed')->orWhere('status', '!=', 'Canceled')->paginate(15);

        return view('livewire.manufacture.jobs.list-livewire', [
            'jobcards_list' => $jobcards_list
        ]);
    }
}
