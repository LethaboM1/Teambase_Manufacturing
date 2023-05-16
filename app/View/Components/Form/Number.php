<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Number extends Component
{
    public $label,
        $name,
        $value,
        $wire,
        $max,
        $min,
        $step,
        $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', $wire = true, $max = '', $min = '', $step = '', $value = '', $class = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->wire = $wire;
        $this->value = $value;
        $this->max = $max;
        $this->min = $min;
        $this->step = $step;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.number');
    }
}
