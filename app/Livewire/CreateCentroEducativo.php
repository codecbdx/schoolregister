<?php

namespace App\Livewire;

use App\Models\Customers;
use Livewire\Component;

class CreateCentroEducativo extends Component
{
    protected $validationAttributes = [
        'responsible' => 'responsable',
        'bank' => 'banco',
        'bank_account_holder' => 'titular',
        'clabe' => 'CLABE',
        'bank_account_number' => 'número de cuenta',
    ];

    public $name, $description, $responsible, $email, $phone, $registration_fee_cost, $bank, $bank_account_holder, $clabe, $bank_account_number;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.create-centro-educativo');
    }

    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'responsible' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'digits_between:7,15'],
            'bank' => ['required', 'string', 'max:255'],
            'bank_account_holder' => ['required', 'string', 'max:255'],
            'clabe' => ['required', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        Customers::create([
            'nombre' => $this->name,
            'descripcion' => $this->description,
            'responsable' => $this->responsible,
            'correo' => $this->email,
            'celular' => $this->phone,
            'banco' => $this->bank,
            'titular' => $this->bank_account_holder,
            'clabe' => $this->clabe,
            'numero_cuenta' => $this->bank_account_number,
            'cancelled' => 0,
        ]);

        $this->redirectRoute('customers');
    }
}
