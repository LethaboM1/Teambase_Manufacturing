<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use App\Models\ManufactureJobcards;
use Livewire\Component;
use Livewire\WithPagination;

class ListLivewire extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tab, $search, $search_arc, $search_filled;

    function mount()
    {
        $this->tab = 'unfilled';
    }

    function updatedSearchArc()
    {
        $this->tab = 'archive';
    }

    function updatedSearch()
    {
        $this->tab = 'unfilled';
    }

    function updatedSearchFilled()
    {
        $this->tab = 'filled';
    }

    public function toggleTab($value)
    {
        $this->tab = $value;
    }

    public function render()
    {      

        $jobcards_list = ManufactureJobcards::where('status', '!=', 'Completed')->where('status', '!=', 'Cancelled')->where('status', '!=', 'Filled')->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('jobcard_number', 'like', $term)
                ->orWhere('contractor', 'like', $term)
                ->orWhere('site_number', 'like', $term);
        })->paginate(15, ['*'], 'unfilled');

        $jobcards_list_filled = ManufactureJobcards::where('status', '!=', 'Completed')->where('status', '!=', 'Cancelled')->where('status', '!=', 'Open')->when($this->search_filled, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('jobcard_number', 'like', $term)
                ->orWhere('contractor', 'like', $term)
                ->orWhere('site_number', 'like', $term);
        })->paginate(15, ['*'], 'filled');

        $archive_list = ManufactureJobcards::where('status', 'Completed')->orWhere('status', 'Cancelled')->when($this->search_arc, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('jobcard_number', 'like', $term)
                ->orWhere('contractor', 'like', $term)
                ->orWhere('site_number', 'like', $term);
        })->paginate(15, ['*'], 'arc');

        //$jobcards_list = ManufactureJobcards::where('status', '!=', 'Completed')->where('status', '!=', 'Canceled')->toSql();

        return view('livewire.manufacture.jobs.list-livewire', [
            'jobcards_list' => $jobcards_list,
            'jobcards_list_filled' => $jobcards_list_filled,
            'archive_list' => $archive_list
        ]);
    }
}
