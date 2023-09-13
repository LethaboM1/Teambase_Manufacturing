<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;

class NewReceiveTransactionsLivewire extends Component
{
    public $transaction, $archive;

    function mount($transaction, $archive = false)
    {
        $this->transaction = $transaction;
        $this->archive = $archive;
    }

    public function render()
    {
        return view('livewire.manufacture.dispatch.new-receive-transactions-livewire');
    }
}
