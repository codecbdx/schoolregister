<?php

namespace App\Livewire;

use App\Models\Customers;
use Livewire\Component;

class CustomerTitle extends Component
{
    public $customers;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->customers = Customers::where('cancelled', 0)->get();
        }
    }

    public function render()
    {
        return view('livewire.customer-title', ['user' => auth()->user()]);
    }
}
