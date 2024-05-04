<?php

namespace App\Livewire;

use App\Exports\AlumnoGrupoExport;
use App\Models\AlumnoGrupo;
use App\Models\ConceptosPago;
use App\Models\Cursos;
use App\Models\Pagos;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Alumnos;
use App\Models\Grupos;
use Illuminate\Support\Facades\Route;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class AsignarAlumnos extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'concepto_pago_alumno' => 'concepto de pago',
        'fecha_vencimiento_alumno' => 'fecha de vencimiento',
        'cargo_alumno' => 'cargo',
    ];

    public $search = '', $options = [], $select_alumno, $alumno, $curp, $btn_edit, $grupo_id, $fecha_inscripcion = '', $list_conceptos_pago, $concepto_pago_alumno = null, $fecha_vencimiento_alumno, $cargo_alumno, $conceptos_pago_grupo, $curso_grupo;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->fillOptions();
            $this->select_alumno = true;
            $this->btn_edit = false;

            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }
            $this->grupo_id = $id;

            $this->conceptos_pago_grupo = Grupos::where('id', $this->grupo_id)->whereIn('cancelled', [0, 2])->first();

            if ($this->conceptos_pago_grupo) {
                $this->curso_grupo = Cursos::where('id', $this->conceptos_pago_grupo->curso_id)->where('cancelled', 0)->first();
            } else {
                $this->curso_grupo = '';
            }

            $this->list_conceptos_pago = ConceptosPago::where('cancelled', 0)->orderBy('nombre', 'asc')->get();
        }
    }


    public function export()
    {
        $nombre_grupo = Grupos::where('id', $this->grupo_id)->where('cancelled', 0)->first();

        return Excel::download(new AlumnoGrupoExport($this->grupo_id), 'Lista de Alumnos Grupo - ' . $nombre_grupo->grupo . '.xlsx');
    }

    public function updatedSearch()
    {
        $this->fillOptions();
        $this->select_alumno = false;
    }

    public function updatedFechaInscripcion()
    {
        $this->fillOptions();
        $this->select_alumno = false;
    }

    protected function fillOptions()
    {
        $this->options = Alumnos::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido_materno', 'like', '%' . $this->search . '%')
                        ->orWhere('curp', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!empty($this->fecha_inscripcion), function ($query) {
                $query->whereDate('created_at', '=', $this->fecha_inscripcion);
            })
            ->where('cancelled', 0)
            ->where('status', '!=', 2)
            ->take(2500)
            ->get();
    }

    #[On('goOn-Save-Asignar-Alumno')]
    public function saveAlumno()
    {
        if ($this->curp !== null) {
            $rules = [
                'alumno' => ['required', 'string', 'max:255'],
            ];

            $this->validate($rules);

            $grupo = Grupos::where('id', $this->grupo_id)->where('cancelled', 0)->first();

            if ($grupo) {
                $cantidad_max_alumnos = $grupo->cantidad_max_alumnos ?? 0;

                // Obtener el conteo de alumnos por grupo que no estén cancelados
                $conteo_alumnos_por_grupo = AlumnoGrupo::where('grupo_id', $this->grupo_id)
                    ->where('cancelled', 0)
                    ->count();

                // Verificar si el grupo tiene espacio disponible para más alumnos
                if ($conteo_alumnos_por_grupo < $cantidad_max_alumnos) {
                    // Buscar si el alumno ya está registrado en el grupo
                    $alumno = AlumnoGrupo::where('curp', $this->curp)
                        ->where('grupo_id', $this->grupo_id)
                        ->where('cancelled', 0)
                        ->first();

                    if (!$alumno) {
                        // El alumno no está registrado en el grupo, crear un nuevo registro
                        AlumnoGrupo::create([
                            'curp' => trim($this->curp),
                            'grupo_id' => trim($this->grupo_id),
                            'cancelled' => 0,
                        ]);
                    } else {
                        // El alumno ya está registrado en el grupo, mostrar un mensaje de error
                        $this->addError('alumno', 'El alumno ingresado ya está registrado en este grupo.');
                    }
                } else {
                    // El grupo está lleno, mostrar un mensaje de error
                    $this->addError('alumno', 'Lo sentimos, el grupo está lleno y no hay espacios disponibles en este momento.');
                }
            } else {
                // No se encontró el grupo, mostrar un mensaje de error
                $this->addError('alumno', 'El grupo al que intentas agregar alumnos no existe o está cancelado.');
            }

            $this->clearForm();
        }
    }

    private function clearForm()
    {
        $this->alumno = '';
        $this->curp = '';
    }

    #[On('goOn-Delete-Asignar-Alumno')]
    public function deleteAlumno($alumnoId)
    {
        $alumno = AlumnoGrupo::where('id', config('app.debug') ? $alumnoId : decrypt($alumnoId))->where('cancelled', 0)->first();
        if ($alumno) {
            $alumno->cancelled = 1;
            $alumno->save();
        }
    }

    public function render()
    {
        $listAlumnosGrupo = AlumnoGrupo::query()
            ->where('grupo_id', $this->grupo_id)
            ->where('cancelled', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener las CURP de los alumnos del grupo actual
        $curps = $listAlumnosGrupo->pluck('curp');

        // Buscar los registros de alumnos que coinciden con las CURP obtenidas
        $alumnos = Alumnos::whereIn('curp', $curps)
            ->get();

        // Asignar los nombres y apellidos a cada alumno del grupo
        foreach ($listAlumnosGrupo as $alumnoGrupo) {
            $alumno = $alumnos->where('curp', $alumnoGrupo->curp)->where('cancelled', 0)->where('status', '!=', 2)->first();
            if ($alumno) {
                $alumnoGrupo->nombre = $alumno->nombre;
                $alumnoGrupo->apellido_paterno = $alumno->apellido_paterno;
                $alumnoGrupo->apellido_materno = $alumno->apellido_materno;
                $alumnoGrupo->usuario_moodle = $alumno->usuario_moodle;
                $alumnoGrupo->contrasena_moodle = $alumno->contrasena_moodle;

                $curso = Cursos::join('grupos', 'cursos.id', '=', 'grupos.curso_id')
                    ->where('grupos.id', $alumnoGrupo->grupo_id)
                    ->where('grupos.cancelled', 0)
                    ->where('cursos.cancelled', 0)
                    ->first();
                if ($curso) {
                    $alumnoGrupo->curso = $curso->nombre;
                    $alumnoGrupo->codigo_moodle = $curso->codigo_moodle;
                }
            }
        }

        return view('livewire.asignar-alumnos', [
            'list_alumnos' => $listAlumnosGrupo,
        ]);
    }

    public function updatedConceptoPagoAlumno($value)
    {
        if ((__('Inscription') . ' - ' . $this->curso_grupo->nombre) == $value) {
            $this->cargo_alumno = $this->conceptos_pago_grupo->inscripcion;
            $this->fecha_vencimiento_alumno = $this->conceptos_pago_grupo->fecha_corte;
        } elseif ($this->curso_grupo->nombre == $value) {
            $this->cargo_alumno = $this->conceptos_pago_grupo->precio_total;
            $this->fecha_vencimiento_alumno = $this->conceptos_pago_grupo->fecha_corte;
        } else {
            $this->cargo_alumno = '';
            $this->fecha_vencimiento_alumno = '';
        }
    }

    public function createConceptoPagoAlumno()
    {
        $rules = [
            'concepto_pago_alumno' => ['required', 'string', 'max:255'],
            'fecha_vencimiento_alumno' => ['required'],
            'cargo_alumno' => ['required', 'numeric', 'between:0,9999999.99'],
        ];

        $this->validate($rules);

        $listAlumnosGrupo = AlumnoGrupo::query()
            ->where('grupo_id', $this->grupo_id)
            ->where('cancelled', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $count_errors = 0;
        foreach ($listAlumnosGrupo as $alumnoGrupo) {
            do {
                $folio_alumno = strtoupper(Str::random(6));
                $conceptoPagoAlumno = Pagos::where('folio', $folio_alumno)->first();
            } while ($conceptoPagoAlumno !== null);

            $concepto_pago_alumno = Pagos::create([
                'folio' => trim($folio_alumno),
                'curp' => trim($alumnoGrupo->curp),
                'fecha_vencimiento' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_vencimiento_alumno))),
                'concepto' => trim($this->concepto_pago_alumno),
                'cargo' => trim($this->cargo_alumno),
                'estado_pago' => 0,
                'usuario_responsable' => auth()->user()->id,
                'customer_id' => auth()->user()->customer_id,
                'cancelled' => 0,
            ]);

            if (!$concepto_pago_alumno) {
                $count_errors++;
            }
        }

        if ($count_errors == 0) {
            $this->reset(['concepto_pago_alumno', 'fecha_vencimiento_alumno', 'cargo_alumno']);

            $this->dispatch('pagoAlumnosCreated');
            $this->dispatch('close-create-concepto-pago-alumno-modal');
        } else {
            $this->reset(['concepto_pago_alumno', 'fecha_vencimiento_alumno', 'cargo_alumno']);

            $this->dispatch('pagoAlumnosWithErrosCreated', $count_errors);
            $this->dispatch('close-create-concepto-pago-alumno-modal');
        }
    }
}
