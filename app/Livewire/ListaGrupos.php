<?php

namespace App\Livewire;

use App\Exports\GruposExport;
use App\Models\AlumnoGrupo;
use App\Models\Alumnos;
use App\Models\Customers;
use App\Models\Modalidad;
use App\Models\User;
use App\Models\UserPermissions;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Grupos;
use App\Models\Cursos;
use PDF;

class ListaGrupos extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'grupo' => 'grupo',
        'inicio_periodo' => 'inicio de periodo',
        'fin_periodo' => 'fin de periodo',
        'fecha_corte' => 'fecha de corte',
        'ciclo' => 'ciclo',
        'modalidad' => 'modalidad',
        'precio_mensualidad' => 'precio de mensualidad',
        'precio_total' => 'precion total',
        'inscripcion' => 'precio de inscripción',
        'curso' => 'curso',
        'cantidad_maxima_alumnos' => 'cantidad máxima de alumnos',
    ];

    protected $listeners = ['goOn-Delete-Grupo' => 'deleteGrupo', 'goOn-Print-Credentials-Group' => 'printCredentialsGroup'];

    public $sortField = 'created_at', $sortAsc = true, $search = '', $totalEntries, $selectedStatus = null, $modalidades, $cursos, $grupo, $inicio_periodo, $fin_periodo, $fecha_corte, $ciclo, $modalidad, $precio_mensualidad, $precio_total, $inscripcion, $curso, $cantidad_maxima_alumnos, $modulePermissions, $filtro_modalidad;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Grupos::totalCount(auth()->user()->customer_id);
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
            $this->modalidades = Modalidad::where('cancelled', 0)->get();
            $this->cursos = Cursos::where('cancelled', 0)->get();
        }
    }

    public function export()
    {
        return Excel::download(new GruposExport($this->filtro_modalidad, $this->selectedStatus), 'Grupos.xlsx');
    }

    public function createGrupo()
    {
        $rules = [
            'grupo' => ['required', 'string', 'max:255'],
            'inicio_periodo' => ['required', 'date'],
            'fin_periodo' => ['required', 'date'],
            'fecha_corte' => ['required', 'date'],
            'ciclo' => ['required', 'string', 'max:255'],
            'modalidad' => ['required', 'string', 'max:255'],
            'precio_mensualidad' => ['required', 'numeric', 'between:0,9999999.99'],
            'precio_total' => ['required', 'numeric', 'between:0,9999999.99'],
            'inscripcion' => ['required', 'numeric', 'between:0,9999999.99'],
            'cantidad_maxima_alumnos' => ['required', 'numeric', 'between:0,999.99'],
            'curso' => ['required', 'integer'],
        ];

        $this->validate($rules);

        Grupos::create([
            'grupo' => trim($this->grupo),
            'inicio_periodo' => date('Y-m-d', strtotime(str_replace('-', '/', $this->inicio_periodo))),
            'fin_periodo' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fin_periodo))),
            'fecha_corte' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_corte))),
            'ciclo' => trim($this->ciclo),
            'modalidad' => trim($this->modalidad),
            'precio_mensualidad' => trim($this->precio_mensualidad),
            'precio_total' => trim($this->precio_total),
            'inscripcion' => trim($this->inscripcion),
            'cantidad_max_alumnos' => trim($this->cantidad_maxima_alumnos),
            'curso_id' => trim($this->curso),
            'customer_id' => auth()->user()->customer_id,
            'cancelled' => 0,
        ]);

        $this->reset(['grupo', 'inicio_periodo', 'fin_periodo', 'fecha_corte', 'ciclo', 'modalidad', 'precio_mensualidad', 'precio_total', 'inscripcion', 'curso', 'cantidad_maxima_alumnos']);

        $this->dispatch('grupoCreated');
        $this->dispatch('close-create-grupo-modal');
    }

    public function search()
    {
        $this->resetPage();
    }

    public function filterByStatus($statusID = null)
    {
        $this->selectedStatus = $statusID;
        $this->search();
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    #[On('goOn-Delete-Grupo')]
    public function deleteGrupo($grupoId)
    {
        $grupo = Grupos::find(config('app.debug') ? $grupoId : decrypt($grupoId));
        if ($grupo) {
            $grupo->cancelled = 1;
            $grupo->save();
        }
    }

    public function updatedFiltroModalidad($value)
    {
        $this->filtro_modalidad = $value;
        $this->search();
    }

    public function render()
    {
        $listAlumnosGrupo = Grupos::search(trim($this->search), auth()->user()->customer_id)
            ->when($this->selectedStatus !== null, function ($query) {
                // Asegúrate de que la condición maneje correctamente el valor 0.
                $query->where('cancelled', $this->selectedStatus);
            })
            ->when($this->filtro_modalidad, function ($query) {
                $query->where('modalidad', $this->filtro_modalidad);
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);

        foreach ($listAlumnosGrupo as $alumnosGrupo) {
            $alumnoGrupo = AlumnoGrupo::where('grupo_id', $alumnosGrupo->id)->where('cancelled', 0)->count();
            if ($alumnoGrupo) {
                $alumnosGrupo->total_alumnos = $alumnoGrupo;
            }
        }

        return view('livewire.lista-grupos', [
            'listGrupos' => $listAlumnosGrupo,
        ]);
    }

    #[On('goOn-Print-Credentials-Group')]
    public function printCredentialsGroup($grupoId)
    {
        $listAlumnosGrupo = AlumnoGrupo::query()
            ->where('grupo_id', config('app.debug') ? $grupoId : decrypt($grupoId))
            ->where('cancelled', 0)
            ->orderBy('created_at', 'desc')
            ->get();

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
                $alumnoGrupo->nombre_tutor = $alumno->nombre_tutor;
                $alumnoGrupo->apellido_paterno_tutor = $alumno->apellido_paterno_tutor;
                $alumnoGrupo->apellido_materno_tutor = $alumno->apellido_materno_tutor;
                $alumnoGrupo->correo = $alumno->correo;
                $alumnoGrupo->telefono_emergencia = $alumno->telefono_emergencia;
                $alumnoGrupo->vigencia = date('d/m/Y', strtotime('+1 year', strtotime(str_replace('-', '/', $alumno->created_at))));

                $user = User::where('email', $alumno->correo)
                    ->where('cancelled', 0)
                    ->first();
                if ($user) {
                    $alumnoGrupo->user_image = $user->user_image;
                }
            }
        }

        $customer = Customers::where('id', auth()->user()->customer_id)->where('cancelled', 0)->first();
        $grupo = Grupos::where('id', config('app.debug') ? $grupoId : decrypt($grupoId))->where('cancelled', 0)->first();

        // Arreglo de datos para pasar a la vista
        $data = [
            'title' => __('Credentials'),
            'address' => $customer->descripcion,
            'telephone' => $customer->celular,
            'alumnos' => $listAlumnosGrupo,
            'grupo' => $grupo->grupo,
        ];

        // Cargar la vista PDF y pasarle los datos
        $pdf = PDF::loadView('pdf/credencialesAlumnosGrupoPDF', $data);

        // Descargar el PDF generado
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, __('Credentials') . ' - ' . $grupo->grupo . '.pdf');
    }
}
