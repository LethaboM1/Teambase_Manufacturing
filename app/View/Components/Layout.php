<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public $page_title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pageTitle)
    {
        // $this->user_role = $userRole;
        $this->page_title = $pageTitle;
        // $this->department = $department;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.layout');
    }
}
