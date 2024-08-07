<?php

namespace App\Livewire\Components;

use App\Models\Customer;
use App\Models\GeminiAI;
use Livewire\Component;

class Config extends Component
{
    public $customer_id;
    public $instruct;
    public $active;

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
        $gemIa = GeminiAI::where('customer_id', $customer_id)->first();
        $this->instruct = $gemIa->instruct ?? '';
        $this->active = $gemIa->active ?? false;
    }

    protected $rules = [
        'instruct' => 'required|string',
        'active' => 'required|in:true,false',
    ];

    public function update()
    {
        $this->validate();
        $gemIa = GeminiAI::where('customer_id', $this->customer_id)->first();
        if ($gemIa) {
            $gemIa->update([
                'instruct' => $this->instruct,
                'active' => $this->active,
            ]);
        } else {
            GeminiAI::create([
                'customer_id' => $this->customer_id,
                'instruct' => $this->instruct,
                'active' => $this->active,
                'session_name' => 'session-'. Customer::where('id', $this->customer_id)->value('whatsapp'),
            ]);
        }
    
        session()->flash('message', 'Configurações atualizadas com sucesso.');
    }
    
    

    public function render()
    {
        return view('livewire.components.config');
    }
}
