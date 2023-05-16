<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Hidden extends Component
{
    public $label,
        $name,
        $value,
        $wire,
        $class,
        $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', $wire = true, $value = '', $id = '', $class = '')
    {
        $this->label = $label;
        $this->name = $name;
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
        return view('components.form.hidden');
    }
}
