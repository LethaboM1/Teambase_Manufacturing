<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use App\Models\ManufactureJobcards;
use Livewire\Component;
use Livewire\WithPagination;

class ListLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tab, $search, $search_arc;

    function mount()
    {
        $this->tab = 'open';
    }

    function updatedSearchArc()
    {
        $this->tab = 'archive';
    }

    function updatedSearch()
    {
        $this->tab = 'open';
    }

    public function render()
    {
        $jobcards_list = ManufactureJobcards::where('status', '!=', 'Completed')->where('status', '!=', 'Canceled')->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('jobcard_number', 'like', $term)
                ->orWhere('contractor', 'like', $term)
                ->orWhere('site_number', 'like', $term);
        })->paginate(15, ['*'], 'open');
        $archive_list = ManufactureJobcards::where('status', 'Completed')->orWhere('status', 'Canceled')->when($this->search_arc, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('jobcard_number', 'like', $term)
                ->orWhere('contractor', 'like', $term)
                ->orWhere('site_number', 'like', $term);
        })->paginate(15, ['*'], 'arc');
        //$jobcards_list = ManufactureJobcards::where('status', '!=', 'Completed')->where('status', '!=', 'Canceled')->toSql();

        return view('livewire.manufacture.jobs.list-livewire', [
            'jobcards_list' => $jobcards_list,
            'archive_list' => $archive_list
        ]);
    }
}
