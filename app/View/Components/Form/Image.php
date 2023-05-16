<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Image extends Component
{
    public $label,
        $name,
        $path,
        $wire,
        $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '',  $wire = true, $path = '', $class = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->wire = $wire;
        $this->path = $path;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.image');
    }
}
