<?php

namespace App\Http\Livewire\Managers;

use App\Http\Controllers\DefaultsController;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    public $search;
    protected $users_list, $paginationTheme = 'bootstrap';

    public function render()
    {
        $users_list = User::where('depart', auth()->user()->depart)->where('role', '!=', 'system')
        /* ->where('active', '!=', '0') */
        ->when($this->search, function ($query, $term) {
            $term = "%{$term}%";
            $query->where('name', 'LIKE', $term)
                ->orWhere('last_name', 'LIKE', $term)
                ->orwhere('company_number', 'LIKE', $term);
        })->orderBy('name')->paginate(10);

        return view('livewire.managers.users', [
            'users_list' => $users_list
        ]);
    }
}
