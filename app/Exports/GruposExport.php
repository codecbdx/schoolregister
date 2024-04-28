<?php

namespace App\Exports;

use App\Models\AlumnoGrupo;
use App\Models\Cursos;
use Carbon\Carbon;
use App\Models\Grupos;
use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GruposExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filtro_modalidad, $selected_status;

    public function __construct($filtro_modalidad, $selected_status)
    {
        $this->filtro_modalidad = $filtro_modalidad;
        $this->selected_status = $selected_status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Grupos::where('cancelled', '!=', 1)->where('customer_id', auth()->user()->customer_id);

        if ($this->filtro_modalidad) {
            $query->where('modalidad', $this->filtro_modalidad);
        }

        if ($this->selected_status !== null) {
            $query->where('cancelled', $this->selected_status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Grupo',
            'Inicio de periodo',
            'Fin de periodo',
            'Fecha corte',
            'Ciclo',
            'Modalidad',
            'Precio de mensualidad',
            'Precio total del curso',
            'Precio de inscripción',
            'Alumnos inscritos',
            'Cantidad máxima de alumnos',
            'Curso',
            'Centro educativo',
            'Estatus',
        ];
    }

    /**
     * @var Grupos $grupo
     */
    public function map($grupo): array
    {
        return [
            $grupo->grupo,
            Carbon::createFromFormat('Y-m-d', $grupo->inicio_periodo)->format('d/m/Y'),
            Carbon::createFromFormat('Y-m-d', $grupo->fin_periodo)->format('d/m/Y'),
            Carbon::createFromFormat('Y-m-d', $grupo->fecha_corte)->format('d/m/Y'),
            $grupo->ciclo,
            $grupo->modalidad,
            $grupo->precio_mensualidad,
            $grupo->precio_total,
            $grupo->inscripcion,
            $this->getCountStudentsByGroup($grupo->id),
            $grupo->cantidad_max_alumnos,
            $this->getCursoName($grupo->curso_id), // Obtener el nombre del curso
            $this->getCustomerName($grupo->customer_id), // Obtener el nombre del centro educativo
            $grupo->cancelled == 0 ? 'Activo' : 'Inactivo',
        ];
    }

    /**
     * Obtiene el total de alumnos dado un grupo_id
     *
     * @param int $grupoId
     * @return string
     */
    private function getCountStudentsByGroup($grupoId): string
    {
        $alumnoGrupo = AlumnoGrupo::where('grupo_id', $grupoId)->where('cancelled', 0)->count();

        return $alumnoGrupo ? $alumnoGrupo : '';
    }

    /**
     * Obtiene el nombre del curso dado un curso_id
     *
     * @param int $cursoId
     * @return string
     */
    private function getCursoName($cursoId): string
    {
        $curso = Cursos::find($cursoId);

        return $curso ? $curso->nombre : '';
    }

    /**
     * Obtiene el nombre del centro educativo dado un customer_id
     *
     * @param int $customerId
     * @return string
     */
    private function getCustomerName($customerId): string
    {
        $customer = Customers::find($customerId);

        return $customer ? $customer->nombre : '';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle('Grupos', false);
            },
        ];
    }
}
