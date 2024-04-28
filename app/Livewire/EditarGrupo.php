<?php

namespace App\Livewire;

use App\Models\Cursos;
use App\Models\Modalidad;
use Livewire\Component;
use App\Models\Grupos;

class EditarGrupo extends Component
{
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

    public $decryptedId, $status, $cursos, $modalidades, $grupo, $inicio_periodo, $fin_periodo, $fecha_corte, $ciclo, $modalidad, $precio_mensualidad, $precio_total, $inscripcion, $curso, $cantidad_maxima_alumnos;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->modalidades = Modalidad::where('cancelled', 0)->get();
            $this->cursos = Cursos::where('cancelled', 0)->get();

            $this->decryptedId = config('app.debug') ? $id : decrypt($id);

            $grupo = Grupos::find($this->decryptedId);
            $this->grupo = $grupo->grupo;
            $this->inicio_periodo = $grupo->inicio_periodo;
            $this->fin_periodo = $grupo->fin_periodo;
            $this->fecha_corte = $grupo->fecha_corte;
            $this->ciclo = $grupo->ciclo;
            $this->modalidad = $grupo->modalidad;
            $this->precio_mensualidad = $grupo->precio_mensualidad;
            $this->precio_total = $grupo->precio_total;
            $this->inscripcion = $grupo->inscripcion;
            $this->curso = $grupo->curso_id;
            $this->cantidad_maxima_alumnos = $grupo->cantidad_max_alumnos;
            $this->status = $grupo->cancelled;
        }
    }

    public function updatedStatus()
    {
        // Asegurarse de que se está trabajando con el grupo correcto
        $grupo = Grupos::find($this->decryptedId);

        if ($grupo) {
            $grupo->cancelled = $this->status ? 0 : 2;
            $grupo->save();
            $this->status = $this->status ? 0 : 2;
        }
    }

    public function render()
    {
        return view('livewire.editar-grupo');
    }

    public function save()
    {
        $grupo = Grupos::find($this->decryptedId);

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

        $grupo->update([
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
        ]);

        $this->dispatch('grupoUpdated');
    }
}
