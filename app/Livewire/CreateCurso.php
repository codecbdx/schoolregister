<?php

namespace App\Livewire;

use App\Models\Cursos;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCurso extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'responsible' => 'responsable',
        'moodle_code' => 'código de moodle',
    ];

    public $name, $description, $moodle_code, $image;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.create-curso');
    }

    public function save()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'moodle_code' => ['required', 'string', 'max:255'],
        ];

        if ($this->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        $this->validate($rules);

        $savedFileName = $this->image ? $this->image->store('courses-pictures', 's3') : null;

        Cursos::create([
            'nombre' => trim($this->name),
            'descripcion' => trim($this->description),
            'codigo_moodle' => trim($this->moodle_code),
            'imagen' => trim($savedFileName),
            'customer_id' => auth()->user()->customer_id,
            'cancelled' => 0,
        ]);

        $this->redirectRoute('courses');
    }
}
