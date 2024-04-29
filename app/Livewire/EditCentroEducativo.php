<?php

namespace App\Livewire;

use App\Models\Customers;
use Livewire\Component;

class EditCentroEducativo extends Component
{
    protected $validationAttributes = [
        'responsible' => 'responsable',
        'bank' => 'banco',
        'bank_account_holder' => 'titular',
        'clabe' => 'CLABE',
        'bank_account_number' => 'número de cuenta',
    ];

    public $name, $description, $responsible, $email, $phone, $bank, $bank_account_holder, $clabe, $bank_account_number, $decryptedId;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->decryptedId = config('app.debug') ? $id : decrypt($id);

        $customer = Customers::find($this->decryptedId);
        $this->name = $customer->nombre;
        $this->description = $customer->descripcion;
        $this->responsible = $customer->responsable;
        $this->email = $customer->correo;
        $this->phone = $customer->celular;
        $this->bank = $customer->banco;
        $this->bank_account_holder = $customer->titular;
        $this->clabe = $customer->clabe;
        $this->bank_account_number = $customer->numero_cuenta;
    }

    public function render()
    {
        return view('livewire.edit-centro-educativo');
    }

    public function save()
    {
        $customer = Customers::find($this->decryptedId);

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

        $customer->update([
            'nombre' => trim($this->name),
            'descripcion' => trim($this->description),
            'responsable' => trim($this->responsible),
            'correo' => trim($this->email),
            'celular' => trim($this->phone),
            'banco' => trim($this->bank),
            'titular' => trim($this->bank_account_holder),
            'clabe' => trim($this->clabe),
            'numero_cuenta' => trim($this->bank_account_number),
        ]);

        $this->redirectRoute('customers');
    }
}
