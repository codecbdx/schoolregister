<?php

namespace App\Livewire;

use App\Models\Estatus;
use App\Models\Grados;
use App\Services\DocumentacionCompletaService;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\MediaSuperior;
use App\Models\Areas;
use App\Models\Alumnos;
use App\Models\EducacionMediaSuperior;
use Illuminate\Support\Facades\Route;

class MediaSuperiorAlumno extends Component
{
    protected $validationAttributes = [
        'curp' => 'CURP',
        'media_superior' => 'educación media superior',
        'area' => 'area terminal',
        'status_media_superior' => 'estatus',
        'grado' => 'grado',
        'promedio' => 'promedio',
    ];

    public $search = '', $options = [], $select_media_superior, $areas, $grados, $lista_estatus, $curp, $media_superior, $area, $status_media_superior, $grado, $promedio, $list_educacion_media_superior, $btn_edit, $educacionMediaSuperiorId, $educacionMediaSuperiorCURP;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->fillOptions();
            $this->areas = Areas::where('cancelled', 0)->get();
            $this->lista_estatus = Estatus::where('cancelled', 0)->get();
            $this->grados = Grados::where('cancelled', 0)->get();
            $this->select_media_superior = true;
            $this->btn_edit = false;

            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }

            $alumno = Alumnos::where('cancelled', 0)->where('id', $id)->first();
            if ($alumno) {
                $this->educacionMediaSuperiorCURP = $alumno->curp;
            }

            $this->list_educacion_media_superior = EducacionMediaSuperior::where('cancelled', 0)->where('curp', $this->educacionMediaSuperiorCURP)->orderBy('updated_at', 'desc')->get();
        }
    }

    public function updatedSearch()
    {
        $this->fillOptions();
        $this->select_media_superior = false;
    }

    public function updatedStatusMediaSuperior($value)
    {
        if ($value == "Finalizada" || $value == "Finalizado") {
            $ultimoGrado = $this->grados->last();

            if ($ultimoGrado) {
                $this->grado = strval($ultimoGrado->grado);
            }
        }
    }

    protected function fillOptions()
    {
        $this->options = MediaSuperior::when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')->where('cancelled', 0);
        })->get();
    }

    #[On('goOn-Save-Educacion-Media-Superior')]
    public function saveMediaSuperior()
    {
        if ($this->curp !== null) {
            $rules = [
                'media_superior' => ['required', 'string', 'max:255'],
                'area' => ['required', 'string', 'max:255'],
                'status_media_superior' => ['required', 'string', 'max:255'],
                'grado' => ['required', 'string', 'max:255'],
                'promedio' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
            ];

            $this->validate($rules);

            EducacionMediaSuperior::create([
                'curp' => trim($this->curp),
                'escuela' => trim($this->media_superior),
                'area_terminal' => trim($this->area),
                'estatus' => trim($this->status_media_superior),
                'grado' => trim($this->grado),
                'promedio_final_aproximado' => trim($this->promedio),
                'cancelled' => 0,
            ]);

            $this->clearForm();

            if (app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->educacionMediaSuperiorCURP)) {
                $conteo = EducacionMediaSuperior::where('curp', $this->educacionMediaSuperiorCURP)
                    ->where('cancelled', 0)
                    ->count();

                if ($conteo === 1) {
                    $alumno = Alumnos::where('curp', $this->educacionMediaSuperiorCURP)->first();

                    if ($alumno) {
                        $alumno->update(['status' => 0]);

                        return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
                    }
                }
            }

            // Recargar los datos de la tabla
            $this->list_educacion_media_superior = EducacionMediaSuperior::where('cancelled', 0)->where('curp', $this->educacionMediaSuperiorCURP)->orderBy('updated_at', 'desc')->get();
        }
    }

    private function clearForm()
    {
        $this->curp = '';
        $this->media_superior = '';
        $this->area = '';
        $this->status_media_superior = '';
        $this->grado = '';
        $this->promedio = '';
    }

    #[On('goOn-Delete-Educacion-Media-Superior')]
    public function deleteEducacion($educacionId)
    {
        $educacion = EducacionMediaSuperior::find(config('app.debug') ? $educacionId : decrypt($educacionId));
        if ($educacion) {
            $educacion->cancelled = 1;
            $educacion->save();

            // Verifica si la documentación está completa después de la eliminación
            if (!app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->educacionMediaSuperiorCURP)) {
                // Actualiza el estado del alumno a "documentación incompleta"
                Alumnos::where('curp', $this->educacionMediaSuperiorCURP)->update(['status' => 2]);
                $conteo = EducacionMediaSuperior::where('curp', $this->educacionMediaSuperiorCURP)
                    ->where('cancelled', 0)
                    ->count();

                if ($conteo === 0) {
                    $alumno = Alumnos::where('curp', $this->educacionMediaSuperiorCURP)->first();

                    if ($alumno) {
                        $alumno->update(['status' => 2]);

                        return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
                    }
                }
            }

            // Recargar los datos de la tabla
            $this->list_educacion_media_superior = EducacionMediaSuperior::where('cancelled', 0)->where('curp', $this->educacionMediaSuperiorCURP)->orderBy('updated_at', 'desc')->get();
        }
    }

    #[On('goOn-Update-Educacion-Media-Superior')]
    public function updateEducacion($educacionId)
    {
        $educacion = EducacionMediaSuperior::find(config('app.debug') ? $educacionId : decrypt($educacionId));
        if ($educacion) {
            $this->media_superior = $educacion->escuela;
            $this->area = $educacion->area_terminal;
            $this->status_media_superior = $educacion->estatus;
            $this->grado = $educacion->grado;
            $this->promedio = $educacion->promedio_final_aproximado;

            $this->btn_edit = true;
            $this->educacionMediaSuperiorId = config('app.debug') ? $educacionId : decrypt($educacionId);
        }
    }

    #[On('goOn-Save-Update-Educacion-Media-Superior')]
    public function saveUpdateEducacion()
    {
        $rules = [
            'media_superior' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'status_media_superior' => ['required', 'string', 'max:255'],
            'grado' => ['required', 'string', 'max:255'],
            'promedio' => ['required', 'regex:/^\d{1,10}(\.\d{1,2})?$/'],
        ];

        $this->validate($rules);

        $educacion = EducacionMediaSuperior::find($this->educacionMediaSuperiorId);
        if ($educacion) {
            $educacion->update([
                'escuela' => trim($this->media_superior),
                'area_terminal' => trim($this->area),
                'estatus' => trim($this->status_media_superior),
                'grado' => trim($this->grado),
                'promedio_final_aproximado' => trim($this->promedio),
            ]);

            $this->btn_edit = false;

            $this->clearForm();

            // Recargar los datos de la tabla
            $this->list_educacion_media_superior = EducacionMediaSuperior::where('cancelled', 0)->where('curp', $this->educacionMediaSuperiorCURP)->orderBy('updated_at', 'desc')->get();

            $this->dispatch('clear-save-update-prompt-educacion-media-superior');
        }
    }

    public function render()
    {
        return view('livewire.media-superior-alumno');
    }
}
