<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Input extends Component
{
    public $label,
        $name,
        $value,
        $disabled,
        $wire,
        $class,
        $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', $id = '', $wire = true, $value = '', $class = '', $disabled = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.input');
    }
}
