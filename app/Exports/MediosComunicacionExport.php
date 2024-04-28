<?php

namespace App\Exports;

use App\Models\MediosComunicacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MediosComunicacionExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MediosComunicacion::where('cancelled', 0)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
        ];
    }

    /**
     * @var MediosComunicacion $area
     */
    public function map($area): array
    {
        return [
            $area->id,
            $area->nombre,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle('Medios de Interacción', false);
            },
        ];
    }
}
