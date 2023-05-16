<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Button extends Component
{
    public $label,
        $name,
        $value,
        $modal,
        $wire,
        $class,
        $submit,
        $href;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label = '', $name = '', $wire = '', $value = 'Submit', $class = '', $modal = '', $submit = false, $href = '')
    {
        $this->label = $label;
        $this->name = $name;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;
        $this->modal = $modal;
        $this->submit = $submit;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.button');
    }
}
