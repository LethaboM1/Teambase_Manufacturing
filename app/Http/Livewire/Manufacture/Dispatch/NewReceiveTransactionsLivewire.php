<?php

namespace App\Http\Livewire\Manufacture\Dispatch;

use Livewire\Component;

class NewReceiveTransactionsLivewire extends Component
{
    public $transaction;

    function mount($transaction)
    {
        $this->transaction = $transaction;
    }

    public function render()
    {
        return view('livewire.manufacture.dispatch.new-receive-transactions-livewire');
    }
}
