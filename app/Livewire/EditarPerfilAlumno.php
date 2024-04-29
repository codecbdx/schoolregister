<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditarPerfilAlumno extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'paternal_lastname' => 'apellido paterno',
        'maternal_lastname' => 'apellido materno',
    ];

    public $nombre, $paternal_lastname, $maternal_lastname, $password, $password_confirmation, $image, $user_image, $decryptedId;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->decryptedId = config('app.debug') ? $id : decrypt($id);

        $user = User::where('email', $this->decryptedId)->first();
        $this->nombre = $user->name;
        $this->paternal_lastname = $user->paternal_lastname;
        $this->maternal_lastname = $user->maternal_lastname;
        $this->user_image = $user->user_image;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.editar-perfil-alumno');
    }

    public function save()
    {
        $user = User::where('email', $this->decryptedId)->first();

        $messages = [
            'email.unique' => 'Este correo eléctronico ya está registrado.',
        ];

        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'paternal_lastname' => ['required', 'string', 'max:255'],
            'maternal_lastname' => ['required', 'string', 'max:255'],
        ];

        if ($this->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }
        if ($this->password !== null) {
            $rules['password'] = 'required|string|min:8|max:100|confirmed';
        }

        $this->validate($rules, $messages);

        $savedFileName = $this->image ? $this->image->store('photos', 's3') : $this->user_image;

        $user->update([
            'name' => trim($this->nombre),
            'paternal_lastname' => trim($this->paternal_lastname),
            'maternal_lastname' => trim($this->maternal_lastname),
            'password' => $this->password ? Hash::make($this->password) : $user->password,
            'user_image' => trim($savedFileName),
        ]);

        $this->dispatch('userUpdated');
    }
}
