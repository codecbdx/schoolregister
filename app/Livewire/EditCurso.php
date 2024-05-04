<?php

namespace App\Livewire;

use App\Models\Cursos;
use App\Services\S3Service;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCurso extends Component
{
    use WithFileUploads;

    protected $s3Service;
    protected $validationAttributes = [
        'responsible' => 'responsable',
        'moodle_code' => 'código de moodle',
    ];

    public $name, $description, $moodle_code, $image, $course_image, $courseSignedImage, $decryptedId;

    public function mount($id, S3Service $s3Service)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->s3Service = $s3Service;
        $this->decryptedId = config('app.debug') ? $id : decrypt($id);

        $course = Cursos::find($this->decryptedId);
        $this->name = $course->nombre;
        $this->description = $course->descripcion;
        $this->moodle_code = $course->codigo_moodle;
        $this->course_image = $course->imagen;

        $signedUrl = $this->s3Service->getPreSignedUrl($this->course_image, 1);
        $this->courseSignedImage = $signedUrl;
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
            'nombre' => trim($this->name),
            'descripcion' => trim($this->description),
            'codigo_moodle' => trim($this->moodle_code),
            'imagen' => trim($savedFileName),
        ]);

        $this->redirectRoute('courses');
    }
}
