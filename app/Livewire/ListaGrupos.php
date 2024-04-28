<?php

namespace App\Livewire;

use App\Exports\GruposExport;
use App\Models\AlumnoGrupo;
use App\Models\Modalidad;
use App\Models\UserPermissions;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Grupos;
use App\Models\Cursos;

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

    protected $listeners = ['goOn-Delete-Grupo' => 'deleteGrupo'];

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
            'grupo' => $this->grupo,
            'inicio_periodo' => date('Y-m-d', strtotime(str_replace('-', '/', $this->inicio_periodo))),
            'fin_periodo' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fin_periodo))),
            'fecha_corte' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_corte))),
            'ciclo' => $this->ciclo,
            'modalidad' => $this->modalidad,
            'precio_mensualidad' => $this->precio_mensualidad,
            'precio_total' => $this->precio_total,
            'inscripcion' => $this->inscripcion,
            'cantidad_max_alumnos' => $this->cantidad_maxima_alumnos,
            'curso_id' => $this->curso,
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

}
