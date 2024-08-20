<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class NewToggle extends Component
{
    public $label,
        $name,
        $value,
        $wire,
        $class,
        $tabindex,
        $id,
        $explicitwire,
        $checked;
        
    
    /**
     * Create a new component instance.
     */
    public function __construct($name, $label = '', $id = '', $wire = true, $value = '', $class = '', $tabindex = '', $explicitwire = '', $checked = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->wire = $wire;
        $this->value = $value;
        $this->class = $class;
        $this->id = $id;
        $this->tabindex = $tabindex;
        $this->explicitwire = $explicitwire;
        $this->checked = $checked;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        
        return view('components.form.new-toggle');
    }
}
