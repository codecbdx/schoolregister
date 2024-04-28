<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Customers;
use App\Models\UserTypes;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUser extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'paternal_lastname' => 'apellido paterno',
        'maternal_lastname' => 'apellido materno',
    ];

    public $name, $paternal_lastname, $maternal_lastname, $email, $password, $password_confirmation, $user_type, $image, $user_image, $decryptedId, $user_types, $user_customer, $customers, $user_status;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->decryptedId = config('app.debug') ? $id : decrypt($id);

        $user = User::find($this->decryptedId);
        $this->name = $user->name;
        $this->paternal_lastname = $user->paternal_lastname;
        $this->maternal_lastname = $user->maternal_lastname;
        $this->email = $user->email;
        $this->user_image = $user->user_image;
        $this->user_type = $user->user_type_id;
        $this->user_types = $this->userTypes = UserTypes::where('id', '!=', 4)->get();
        $this->user_customer = $user->customer_id;
        $this->customers = Customers::where('cancelled', 0)->get();
        $this->user_status = $user->cancelled;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.edit-user');
    }

    public function save()
    {
        $user = User::find($this->decryptedId);

        $messages = [
            'email.unique' => 'Este correo eléctronico ya está registrado.',
        ];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'paternal_lastname' => ['required', 'string', 'max:255'],
            'maternal_lastname' => ['required', 'string', 'max:255'],
        ];

        if ($this->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }
        if ($this->email !== $user->email) {
            $rules['email'] = 'required|string|email|max:255|unique:users';
        } else {
            $rules['email'] = 'required|string|email|max:255';
        }
        if ($this->password !== null) {
            $rules['password'] = 'required|string|min:8|max:100|confirmed';
        }

        $this->validate($rules, $messages);

        $savedFileName = $this->image ? $this->image->store('photos', 's3') : $this->user_image;

        $user->update([
            'name' => $this->name,
            'paternal_lastname' => $this->paternal_lastname,
            'maternal_lastname' => $this->maternal_lastname,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : $user->password,
            'user_image' => $savedFileName,
            'user_type_id' => $this->user_type,
            'customer_id' => $this->user_customer,
            'cancelled' => $this->user_status,
        ]);

        $this->redirectRoute('users');
    }
}
