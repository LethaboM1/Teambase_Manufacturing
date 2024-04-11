<?php

namespace App\View\Components\Managers\Users;

use App\Http\Controllers\DefaultsController;
use Illuminate\View\Component;

class View extends Component
{
    public $user, $roles_list;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        if ($user == null) {
            $this->user = [
                'name' => '',
                'last_name' => '',
                'employee_number' => '',
                'id_number' => '',
                'employee_number' => '',
                'company_number' => '',
                'contact_number' => '',
                'email' => '',
                'username' => '',
                'password' => '',
                'role' => '',
                'active' => 1,
                'out_of_office' => 0,
            ];
        } else {
            $this->user = $user;
        }        

        $this->roles_list = DefaultsController::roles[auth()->user()->depart];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.managers.users.view');
    }
}
