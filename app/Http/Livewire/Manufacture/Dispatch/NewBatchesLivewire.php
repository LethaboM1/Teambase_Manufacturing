<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ManufactureJobcardProductDispatches;
use App\Models\ManufactureJobcardProducts;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProductTransactions;

class NewBatchesLivewire extends Component
{
    use WithPagination;
    public $tab, $search, $search_arc, $search_receive_goods;

    protected $listeners = [
        'refreshNewDispatch', 'refreshArchiveDispatch', 'toggleTab'
    ], $paginationTheme = 'bootstrap';

    function mount()
    {
        if (session()->get('tab')) {
            if (in_array(session()->get('tab'), ['loading', 'archive'])) {
                $this->tab = session()->get('tab');
            }
        } else {
            $this->tab = 'loading';
        }
    }    

    function refreshNewDispatchModal (){        
        //Reload Modal        
        return redirect(request()->header('Referer'));
    }

    function refreshNewDispatch()
    {        
        $this->resetPage();
    }

    function refreshArchiveDispatch()
    {
        $this->resetPage();
        $this->tab = 'archive';
    }

    function updatedSearchArc()
    {
        $this->tab = 'archive';
    }

    public function toggleTab($value)
    {
        $this->tab = $value;
    }

    function updatedSearch()
    {
        $this->tab = 'loading';
    }

    public function render()
    {

        $dispatches = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
        ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')       
        ->select('dispatches.*','jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
        ->where('dispatches.status','Loading')
        ->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query
                ->where('dispatches.dispatch_number', 'like', $term)                
                ->orWhere('dispatches.reference', 'like', $term)
                ->orWhere('dispatches.registration_number', 'like', $term)
                // ->orWhere('dispatches.status', 'like', $term)                
                ->orWhere('jobs.jobcard_number', 'like', $term)
                ->orWhere('jobs.site_number', 'like', $term);
        })
        ->orderBy('id', 'desc')
        ->paginate(15, ['*'], 'loading');   

        $dispatches_archived = ManufactureJobcardProductDispatches::from('manufacture_jobcard_product_dispatches as dispatches')
        ->join('manufacture_jobcards as jobs', 'jobs.id', '=', 'dispatches.job_id', 'left outer')       
        ->select('dispatches.*','jobs.jobcard_number as jobcard_number','jobs.site_number as site_number')
        ->where('dispatches.status', '!=', 'Loading')
        ->where('dispatches.status', '!=', 'Deleted')
        ->when($this->search_arc, function ($query, $term) {
            $term = "%{$term}%";
            $query
                ->where('dispatches.dispatch_number', 'like', $term)
                ->orWhere('dispatches.reference', 'like', $term)
                ->orWhere('dispatches.registration_number', 'like', $term)
                // ->orWhere('status', 'like', $term)
                ->orWhere('jobs.jobcard_number', 'like', $term)
                ->orWhere('jobs.site_number', 'like', $term);
        })
        ->orderBy('id', 'desc')
        ->paginate(15, ['*'], 'archived');

// dd($dispatches);
        return view('livewire.manufacture.dispatch.new-batches-livewire', [
            'dispatches' => $dispatches,
            'dispatches_archived' => $dispatches_archived,
            /* 'product_transactions' => $product_transactions */ //Moved to Receiving List
        ]);
    }
}
