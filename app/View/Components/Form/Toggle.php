<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Toggle extends Component
{
    public $label,
        $name,
        $idd,
        $value,
        $toggle,
        $wire,
        $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '',  $idd = '', $toggle = true, $wire = true, $value = '', $class = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->idd = $idd;
        $this->toggle = $toggle;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.toggle');
    }
}
