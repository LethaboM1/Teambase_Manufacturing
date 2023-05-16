<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Submit extends Component
{
    public $label,
        $name,
        $value,
        $wire,
        $class,
        $disabled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', $wire = '', $value = 'Submit', $class = '', $disabled = false)
    {
        $this->name = $name;
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
        return view('components.form.submit');
    }
}
