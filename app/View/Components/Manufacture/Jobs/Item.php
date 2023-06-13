<?php

namespace App\View\Components\Manufacture\Jobs;

use Illuminate\View\Component;

class Item extends Component
{
    public $jobcard;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($jobcard)
    {
        $this->jobcard = $jobcard;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.manufacture.jobs.item');
    }
}
