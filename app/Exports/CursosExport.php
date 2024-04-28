<?php

namespace App\Exports;

use App\Models\Cursos;
use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CursosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Cursos::where('cancelled', 0)->where('customer_id', auth()->user()->customer_id)->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Descripción',
            'Código Moodle',
            'Centro educativo',
        ];
    }

    /**
     * @var Cursos $curso
     */
    public function map($curso): array
    {
        return [
            $curso->nombre,
            $curso->descripcion,
            $curso->codigo_moodle,
            $this->getCustomerName($curso->customer_id), // Obtener el nombre del centro educativo
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
                $event->sheet->getDelegate()->setTitle('Cursos', false);
            },
        ];
    }
}
