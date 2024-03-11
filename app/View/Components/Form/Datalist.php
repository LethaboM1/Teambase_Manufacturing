<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Datalist extends Component
{
    public $label,
        $name,
        $value,
        $disabled,
        $wire,
        $list,
        $class,
        $id;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label = '', /* $id = '', */ $wire = true, $value = '', $class = '', $disabled = false, $list=[])
    {
        $this->name = $name;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;
        /* $this->id = $id; */
        $this->list = $list;        
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {        
        return view('components.form.datalist');
    }
}
