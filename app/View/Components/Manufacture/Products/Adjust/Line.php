<?php

namespace App\View\Components\Manufacture\Products\Adjust;

use App\Models\User;
use Illuminate\View\Component;

class Line extends Component
{
    public $line, $user;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($line)
    {
        $this->line = $line;
        $this->user = User::where('user_id', $line->user_id)->first();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        
        return view('components.manufacture.products.adjust.line');
    }
}
