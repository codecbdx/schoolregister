<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Alumnos;
use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AlumnosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filtro_fecha_inscripcion, $selected_status;

    public function __construct($filtro_fecha_inscripcion, $selected_status)
    {
        $this->filtro_fecha_inscripcion = $filtro_fecha_inscripcion;
        $this->selected_status = $selected_status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Alumnos::where('cancelled', 0)->where('customer_id', auth()->user()->customer_id);

        if ($this->filtro_fecha_inscripcion) {
            $query->whereDate('created_at', $this->filtro_fecha_inscripcion);
        }

        if ($this->selected_status !== null) {
            $query->where('status', $this->selected_status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Fecha de inscripción',
            'CURP',
            'Nombre',
            'Apellido paterno',
            'Apellido materno',
            'Fecha de nacimiento',
            'Sexo',
            'Teléfono del alumno',
            'Teléfono de emergencia',
            'Correo',
            'Facebook',
            'Instagram',
            'Nombre del tutor',
            'Apellido paterno del tutor',
            'Apellido materno del tutor',
            'Parentesco del tutor',
            'Teléfono del tutor',
            'Medio de interacción',
            'Estatus',
            'Centro educativo',
        ];
    }

    /**
     * @var Alumnos $alumno
     */
    public function map($alumno): array
    {
        return [
            Carbon::parse($alumno->created_at)->format('d/m/Y'),
            $alumno->curp,
            $alumno->nombre,
            $alumno->apellido_paterno,
            $alumno->apellido_materno,
            Carbon::createFromFormat('Y-m-d', $alumno->fecha_nacimiento)->format('d/m/Y'),
            $alumno->sexo,
            $alumno->telefono_alumno,
            $alumno->telefono_emergencia,
            $alumno->correo,
            $alumno->facebook,
            $alumno->instagram,
            $alumno->nombre_tutor,
            $alumno->apellido_paterno_tutor,
            $alumno->apellido_materno_tutor,
            $alumno->parentesco_tutor,
            $alumno->telefono_tutor,
            $alumno->medio_interaccion,
            $alumno->status == 1 ? 'Activo' : 'Baja',
            $this->getCustomerName($alumno->customer_id), // Obtener el nombre del centro educativo
        ];
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
                $event->sheet->getDelegate()->setTitle('Alumnos', false);
            },
        ];
    }
}
