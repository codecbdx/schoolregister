<?php

namespace App\Livewire;

use App\Models\AlumnoGrupo;
use App\Models\Cursos;
use App\Models\Modalidad;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Alumnos;
use App\Models\Grupos;
use Illuminate\Support\Facades\Route;
use Livewire\WithPagination;

class AsignarGrupos extends Component
{
    use WithPagination;

    public $search = '', $options = [], $select_grupo, $grupo, $btn_edit, $alumno_id, $modalidad = '', $modalidades;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->fillOptions();
            $this->select_grupo = true;
            $this->btn_edit = false;

            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }
            $id = Alumnos::where('id', $id)->where('cancelled', 0)->first();
            $this->alumno_id = $id->curp;
            $this->modalidades = Modalidad::where('cancelled', 0)->get();
        }
    }

    public function updatedSearch()
    {
        $this->fillOptions();
        $this->select_grupo = false;
    }

    public function updatedModalidad()
    {
        $this->fillOptions();
        $this->select_grupo = false;
    }

    protected function fillOptions()
    {
        $this->options = Grupos::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('grupo', 'like', '%' . $this->search . '%')
                        ->orWhere('ciclo', 'like', '%' . $this->search . '%')
                        ->orWhere('modalidad', 'like', '%' . $this->search . '%');
                });
            })
            ->when(!empty($this->modalidad), function ($query) {
                $query->where('modalidad', '=', $this->modalidad);
            })
            ->where('cancelled', 0)
            ->take(2500)
            ->get();
    }

    #[On('goOn-Save-Asignar-Grupo')]
    public function saveAlumno()
    {
        if ($this->alumno_id !== null) {
            $rules = [
                'grupo' => ['required', 'integer'],
            ];

            $this->validate($rules);

            $grupo = Grupos::where('id', $this->grupo)->where('cancelled', 0)->first();

            if ($grupo) {
                $cantidad_max_alumnos = $grupo->cantidad_max_alumnos ?? 0;

                // Obtener el conteo de alumnos por grupo que no estén cancelados
                $conteo_alumnos_por_grupo = AlumnoGrupo::where('grupo_id', $this->grupo)
                    ->where('cancelled', 0)
                    ->count();

                // Verificar si el grupo tiene espacio disponible para más alumnos
                if ($conteo_alumnos_por_grupo < $cantidad_max_alumnos) {
                    // Buscar si el alumno ya está registrado en el grupo
                    $alumno = AlumnoGrupo::where('curp', $this->alumno_id)
                        ->where('grupo_id', $this->grupo)
                        ->where('cancelled', 0)
                        ->first();

                    if (!$alumno) {
                        // El alumno no está registrado en el grupo, crear un nuevo registro
                        AlumnoGrupo::create([
                            'curp' => $this->alumno_id,
                            'grupo_id' => $this->grupo,
                            'cancelled' => 0,
                        ]);
                    } else {
                        // El grupo ya está registrado en el grupo, mostrar un mensaje de error
                        $this->addError('grupo', 'El alumno ingresado ya está registrado en este grupo.');
                    }
                } else {
                    // El grupo está lleno, mostrar un mensaje de error
                    $this->addError('grupo', 'Lo sentimos, el grupo está lleno y no hay espacios disponibles en este momento.');
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
        $this->grupo = '';
    }

    #[On('goOn-Delete-Asignar-Grupo')]
    public function deleteAlumno($grupoId)
    {
        $grupo = AlumnoGrupo::where('id', config('app.debug') ? $grupoId : decrypt($grupoId))->where('cancelled', 0)->first();
        if ($grupo) {
            $grupo->cancelled = 1;
            $grupo->save();
        }
    }

    public function render()
    {
        $listAlumnosGrupo = AlumnoGrupo::query()
            ->where('curp', $this->alumno_id)
            ->where('cancelled', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener los IDS de los grupos del grupo actual
        $ids = $listAlumnosGrupo->pluck('grupo_id');

        // Buscar los registros de grupos que coinciden con los IDS obtenidos
        $grupos = Grupos::whereIn('id', $ids)
            ->get();

        // Asignar datos del grupo
        foreach ($listAlumnosGrupo as $alumnoGrupo) {
            $grupo = $grupos->where('id', $alumnoGrupo->grupo_id)->where('cancelled', 0)->first();
            if ($grupo) {
                $alumnoGrupo->grupo = $grupo->grupo;
                $alumnoGrupo->ciclo = $grupo->ciclo;
                $alumnoGrupo->modalidad = $grupo->modalidad;

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

        return view('livewire.asignar-grupos', [
            'list_grupos' => $listAlumnosGrupo,
        ]);
    }
}
