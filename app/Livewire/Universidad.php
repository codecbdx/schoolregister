<?php

namespace App\Livewire;

use App\Models\Alumnos;
use App\Services\DocumentacionCompletaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Universidades;
use App\Models\Carreras;
use App\Models\UniversidadesAlumno;

class Universidad extends Component
{
    protected $validationAttributes = [
        'curp' => 'CURP',
        'universidad' => 'universidad',
        'licenciatura' => 'licenciatura',
        'fecha_examen' => 'fecha de examen',
    ];

    public $search = '', $options = [], $select_universidad, $carreras, $curp, $universidad, $licenciatura, $fecha_examen, $list_universidades_alumno, $btn_edit, $universidad_alumno_id, $curpAlumno;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->fillOptions();
            $this->carreras = Carreras::where('cancelled', 0)->get();
            $this->select_universidad = true;
            $this->btn_edit = false;

            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }

            $alumno = Alumnos::where('cancelled', 0)->where('id', $id)->first();
            if ($alumno) {
                $this->curpAlumno = $alumno->curp;
            }

            $this->list_universidades_alumno = UniversidadesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    public function updatedSearch()
    {
        $this->fillOptions();
        $this->select_universidad = false;
    }

    protected function fillOptions()
    {
        $this->options = Universidades::when($this->search, function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')->where('cancelled', 0);
        })->get();
    }

    #[On('goOn-Save-Universidad-Alumno')]
    public function saveUniversidadAlumno()
    {
        if ($this->curp !== null) {
            $rules = [
                'universidad' => ['required', 'string', 'max:255'],
                'licenciatura' => ['required', 'string', 'max:255'],
                'fecha_examen' => ['required'],
            ];

            $this->validate($rules);

            UniversidadesAlumno::create([
                'curp' => trim($this->curp),
                'universidad' => trim($this->universidad),
                'licenciatura' => trim($this->licenciatura),
                'fecha_examen' => trim($this->fecha_examen),
                'cancelled' => 0,
            ]);

            $this->clearForm();

            if (app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curpAlumno)) {
                $conteo = UniversidadesAlumno::where('curp', $this->curpAlumno)
                    ->where('cancelled', 0)
                    ->count();

                if ($conteo === 1) {
                    $alumno = Alumnos::where('curp', $this->curpAlumno)->first();

                    if ($alumno) {
                        $alumno->update(['status' => 0]);

                        return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
                    }
                }
            }

            // Recargar los datos de la tabla
            $this->list_universidades_alumno = UniversidadesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    private function clearForm()
    {
        $this->curp = '';
        $this->universidad = '';
        $this->licenciatura = '';
        $this->fecha_examen = '';
    }

    #[On('goOn-Delete-Universidad-Alumno')]
    public function deleteUniversidadAlumno($universidadAlumnoId)
    {
        $universidad_alumno = UniversidadesAlumno::find(config('app.debug') ? $universidadAlumnoId : decrypt($universidadAlumnoId));
        if ($universidad_alumno) {
            $universidad_alumno->cancelled = 1;
            $universidad_alumno->save();

            // Verifica si la documentación está completa después de la eliminación
            if (!app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curpAlumno)) {
                // Actualiza el estado del alumno a "documentación incompleta"
                Alumnos::where('curp', $this->curpAlumno)->update(['status' => 2]);
                $conteo = UniversidadesAlumno::where('curp', $this->curpAlumno)
                    ->where('cancelled', 0)
                    ->count();

                if ($conteo === 0) {
                    $alumno = Alumnos::where('curp', $this->curpAlumno)->first();

                    if ($alumno) {
                        $alumno->update(['status' => 2]);

                        return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
                    }
                }
            }

            // Recargar los datos de la tabla
            $this->list_universidades_alumno = UniversidadesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    #[On('goOn-Update-Universidad-Alumno')]
    public function updateUniversidadAlumno($universidadAlumnoId)
    {
        $universidad_alumno = UniversidadesAlumno::find(config('app.debug') ? $universidadAlumnoId : decrypt($universidadAlumnoId));
        if ($universidad_alumno) {
            $this->universidad = $universidad_alumno->universidad;
            $this->licenciatura = $universidad_alumno->licenciatura;
            $this->fecha_examen = $this->formatDate($universidad_alumno->fecha_examen);

            $this->btn_edit = true;
            $this->universidad_alumno_id = config('app.debug') ? $universidadAlumnoId : decrypt($universidadAlumnoId);
        }
    }

    #[On('goOn-Save-Update-Universidad-Alumno')]
    public function saveUpdateUniversidadAlumno()
    {
        $rules = [
            'universidad' => ['required', 'string', 'max:255'],
            'licenciatura' => ['required', 'string', 'max:255'],
            'fecha_examen' => ['required'],
        ];

        $this->validate($rules);

        $universidad_alumno = UniversidadesAlumno::find($this->universidad_alumno_id);
        if ($universidad_alumno) {
            $universidad_alumno->update([
                'universidad' => trim($this->universidad),
                'licenciatura' => trim($this->licenciatura),
                'fecha_examen' => trim($this->fecha_examen),
            ]);

            $this->btn_edit = false;

            $this->clearForm();

            // Recargar los datos de la tabla
            $this->list_universidades_alumno = UniversidadesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();

            $this->dispatch('clear-save-update-prompt-universidad-alumno');
        }
    }

    private function formatDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.universidad');
    }
}
