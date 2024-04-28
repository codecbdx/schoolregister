<?php

namespace App\Livewire;

use App\Models\Cursos;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCurso extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'responsible' => 'responsable',
        'moodle_code' => 'código de moodle',
    ];

    public $name, $description, $moodle_code, $image, $course_image, $decryptedId;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->decryptedId = config('app.debug') ? $id : decrypt($id);

        $course = Cursos::find($this->decryptedId);
        $this->name = $course->nombre;
        $this->description = $course->descripcion;
        $this->moodle_code = $course->codigo_moodle;
        $this->course_image = $course->imagen;
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.edit-curso', ['courseImage' => Cursos::find($this->decryptedId) ? Cursos::find($this->decryptedId)->imagen : 'load.gif']);
    }

    public function save()
    {
        $course = Cursos::find($this->decryptedId);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'moodle_code' => ['required', 'string', 'max:255'],
        ];

        if ($this->image) {
            $rules['image'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
        }

        $this->validate($rules);

        $savedFileName = $this->image ? $this->image->store('courses-pictures', 's3') : $this->course_image;

        $course->update([
            'nombre' => $this->name,
            'descripcion' => $this->description,
            'codigo_moodle' => $this->moodle_code,
            'imagen' => $savedFileName,
        ]);

        $this->redirectRoute('courses');
    }
}
