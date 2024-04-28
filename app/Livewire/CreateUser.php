<?php

namespace App\Livewire;

use App\Models\Customers;
use App\Models\UserTypes;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class CreateUser extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'paternal_lastname' => 'apellido paterno',
        'maternal_lastname' => 'apellido materno',
        'user_type' => 'rol de usuario',
        'user_customer' => 'centro educativo',
    ];

    public $name, $paternal_lastname, $maternal_lastname, $email, $password, $password_confirmation, $image, $user_type, $userTypes, $user_customer, $customers;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $this->userTypes = UserTypes::where('id', '!=', 4)->get();
        $this->customers = Customers::where('cancelled', 0)->get();
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.create-user');
    }

    public function save()
    {
        $messages = [
            'email.unique' => 'Este correo eléctronico ya está registrado.',
        ];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'paternal_lastname' => ['required', 'string', 'max:255'],
            'maternal_lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            'user_type' => ['required'],
            'user_customer' => ['required'],
        ];

        if ($this->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        $this->validate($rules, $messages);

        $savedFileName = $this->image ? $this->image->store('photos', 's3') : 'photos/user.png';

        User::create([
            'name' => $this->name,
            'paternal_lastname' => $this->paternal_lastname,
            'maternal_lastname' => $this->maternal_lastname,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'customer_id' => $this->user_customer,
            'user_type_id' => $this->user_type,
            'user_image' => $savedFileName,
            'cancelled' => 0,
        ]);

        $this->redirectRoute('users');
    }
}
