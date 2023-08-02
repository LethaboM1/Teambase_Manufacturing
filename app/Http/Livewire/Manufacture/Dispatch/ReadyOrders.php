<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use App\Models\ManufactureJobcardProductDispatches;
use Livewire\Component;
use Livewire\WithPagination;

class ReadyOrders extends Component
{
    use WithPagination;
    protected $paginateTheme = 'bootstrap';

    public $drivers;



    public function render()
    {
        $dispatches = ManufactureJobcardProductDispatches::where('status', 'Ready')->paginate(15);
        return view('livewire.manufacture.dispatch.ready-orders', [
            'dispatches' => $dispatches
        ]);
    }
}
