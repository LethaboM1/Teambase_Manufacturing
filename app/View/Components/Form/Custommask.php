<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Custommask extends Component
{
    public $label,
        $name,
        $value,
        $wire,
        $class,
        $themask,
        $themaskplaceholder;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', $wire = true, $value = '', $class = '', $themask = '', $themaskplaceholder = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->wire = $wire;
        $this->value = $value;
        $this->themask = $themask;
        $this->themaskplaceholder = $themaskplaceholder;
        $this->class = $class;       
        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // dd($this->themask);
        return view('components.form.custommask');
    }
}
