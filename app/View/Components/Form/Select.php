<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select extends Component
{
    public $label,
        $name,
        $list,
        $value,
        $wire,
        $class,
        $disabled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $list, $label = '',  $wire = true, $value = '', $class = '', $disabled = 0)
    {
        $this->name = $name;
        $this->list = $list;
        $this->label = $label;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select');
    }
}
