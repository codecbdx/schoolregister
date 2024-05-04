<?php

namespace App\Livewire;

use App\Models\ConfiguracionGeneral;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditConfiguracionGeneral extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'name' => 'nombre del sistema',
    ];

    public $name, $system_logo, $system_icon, $background_login, $form_image, $current_system_logo, $current_system_icon, $current_background_login, $current_form_image;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $system = ConfiguracionGeneral::find(1);
        $this->name = $system->system_name;
        $this->current_system_logo = $system->system_logo;
        $this->current_system_icon = $system->system_icon;
        $this->current_background_login = $system->background_login;
        $this->current_form_image = $system->form_image;
    }

    public function updatedImage()
    {
        $this->validate([
            'system_logo' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'system_icon' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'background_login' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'form_image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.edit-configuracion-general');
    }

    public function save()
    {
        $system = ConfiguracionGeneral::find(1);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if ($this->system_logo) {
            $rules['system_logo'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        if ($this->system_icon) {
            $rules['system_icon'] = 'image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        if ($this->background_login) {
            $rules['background_login'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        if ($this->form_image) {
            $rules['form_image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        $this->validate($rules);

        $savedFileSystemLogo = $this->system_logo ? $this->system_logo->store('system-pictures', 'public') : $this->current_system_logo;
        $savedFileSystemIcon = $this->system_icon ? $this->system_icon->store('system-pictures', 'public') : $this->current_system_icon;
        $savedFileSystemBackgroundLogin = $this->background_login ? $this->background_login->store('system-pictures', 'public') : $this->current_background_login;
        $savedFileFormImage = $this->form_image ? $this->form_image->store('system-pictures', 'public') : $this->current_form_image;

        $system->update([
            'system_name' => $this->name,
            'system_logo' => $savedFileSystemLogo,
            'system_icon' => $savedFileSystemIcon,
            'background_login' => $savedFileSystemBackgroundLogin,
            'form_image' => $savedFileFormImage,
        ]);

        $this->redirectRoute('general_configuration');
    }
}
