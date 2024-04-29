<?php

namespace App\Livewire;

use App\Models\Alumnos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\CodigosPostales;
use App\Models\DireccionesAlumno;
use App\Services\DocumentacionCompletaService;

class Direcciones extends Component
{
    protected $validationAttributes = [
        'curp' => 'CURP',
        'codigo_postal' => 'código postal',
        'calle' => 'calle',
        'asentamiento' => 'asentamiento',
        'tipo_asentamiento' => 'tipo de asentamiento',
        'municipio' => 'municipio',
        'estado' => 'estado',
        'search' => 'código postal',
    ];

    public $search = '', $options = [], $select_asentamiento, $curp, $codigo_postal, $calle, $asentamiento, $tipo_asentamiento, $municipio, $estado, $list_direcciones_alumno, $btn_edit, $curpAlumno, $list_asentamiento;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->fillOptions();
            $this->select_asentamiento = true;
            $this->btn_edit = false;

            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }

            $alumno = Alumnos::where('cancelled', 0)->where('id', $id)->first();
            if ($alumno) {
                $this->curpAlumno = $alumno->curp;
            }

            $this->list_direcciones_alumno = DireccionesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    #[On('goOn-Changed-List-Asentamiento')]
    public function changedListAsentamiento()
    {
        if ($this->search != '') {
            $tipoAsentamiento = CodigosPostales::where('asentamiento', $this->list_asentamiento)->where('codigo', $this->search)->first();

            $this->asentamiento = $tipoAsentamiento ? $tipoAsentamiento->asentamiento : '';
            $this->tipo_asentamiento = $tipoAsentamiento ? $tipoAsentamiento->tipo_asentamiento : '';
            $this->municipio = $tipoAsentamiento ? $tipoAsentamiento->municipio : '';
            $this->estado = $tipoAsentamiento ? $tipoAsentamiento->estado : '';
            $this->codigo_postal = $tipoAsentamiento ? $tipoAsentamiento->codigo : '';

            $this->resetErrorBag('search');
        } else {
            $this->addError('search', 'El campo código postal es obligatorio.');
        }
    }

    public function updatedSearch()
    {
        $this->fillOptions();
        $this->select_asentamiento = false;
    }

    protected function fillOptions()
    {
        $this->options = CodigosPostales::when($this->search, function ($query) {
            return $query->where('codigo', 'like', '%' . $this->search . '%');
        })->take(999)->get();
    }

    public function saveDireccionAlumno()
    {
        if ($this->curp !== null) {
            $rules = [
                'search' => ['required', 'numeric'],
                'calle' => ['required', 'string', 'max:255'],
                'asentamiento' => ['required', 'string', 'max:255'],
                'tipo_asentamiento' => ['required', 'string', 'max:255'],
                'municipio' => ['required', 'string', 'max:255'],
                'estado' => ['required', 'string', 'max:255'],
            ];

            $this->validate($rules);

            DireccionesAlumno::create([
                'curp' => trim($this->curp),
                'codigo_postal' => trim($this->codigo_postal) != '' ? trim($this->codigo_postal) : trim($this->search),
                'calle' => trim($this->calle),
                'asentamiento' => trim($this->asentamiento),
                'tipo_asentamiento' => trim($this->tipo_asentamiento),
                'municipio' => trim($this->municipio),
                'estado' => trim($this->estado),
                'cancelled' => 0,
            ]);

            $this->clearForm();

            if (app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curpAlumno)) {
                $conteo = DireccionesAlumno::where('curp', $this->curpAlumno)
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

            $this->search = '';
            $this->fillOptions();
            $this->select_asentamiento = true;
            $this->list_asentamiento = '';

            // Recargar los datos de la tabla
            $this->list_direcciones_alumno = DireccionesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    private function clearForm()
    {
        $this->curp = '';
        $this->codigo_postal = '';
        $this->calle = '';
        $this->asentamiento = '';
        $this->tipo_asentamiento = '';
        $this->municipio = '';
        $this->estado = '';
    }

    #[On('goOn-Delete-Direccion-Alumno')]
    public function deleteDireccionAlumno($direccionAlumnoId)
    {
        $direccion_alumno = DireccionesAlumno::find(config('app.debug') ? $direccionAlumnoId : decrypt($direccionAlumnoId));
        if ($direccion_alumno) {
            $direccion_alumno->cancelled = 1;
            $direccion_alumno->save();

            // Verifica si la documentación está completa después de la eliminación
            if (!app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curpAlumno)) {
                // Actualiza el estado del alumno a "documentación incompleta"
                Alumnos::where('curp', $this->curpAlumno)->update(['status' => 2]);
                $conteo = DireccionesAlumno::where('curp', $this->curpAlumno)
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
            $this->list_direcciones_alumno = DireccionesAlumno::where('cancelled', 0)->where('curp', $this->curpAlumno)->orderBy('updated_at', 'desc')->get();
        }
    }

    public function render()
    {
        return view('livewire.direcciones');
    }
}
