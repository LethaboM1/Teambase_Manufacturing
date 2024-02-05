<?php

namespace App\Http\Livewire\Managers;

use App\Models\Settings as ModelsSettings;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    public
    $settings_rows,
    $logo, 
    $company_logo,
    $company_details;

    use WithFileUploads;
    
    public function mount()
    {
        $this->company_details = ModelsSettings::get()->first()->toArray();        
        if(!is_null($this->company_details)){$this->settings_rows = 1;} else {$this->settings_rows = 0;}       

        $this->company_logo = $this->company_details['logo'];
        
    }

    public function updatedLogo (){
        $this->validate([
            'logo' => 'image|max:10240',
        ]);        
        
        $name = 'logo.'.$this->logo->extension();

        $this->logo->storeAs('logos', $name);
        
        $this->company_logo = $name;       
    
    }   
    
    public function render()
    {
    
        return view('livewire.managers.settings');
    }
}
