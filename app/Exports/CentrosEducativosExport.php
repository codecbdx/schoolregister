<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CentrosEducativosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customers::where('cancelled', 0)->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Descripción',
            'Responsable',
            'Correo electrónico',
            'Celular',
            'Banco',
            'Titular',
            'Clabe',
            'Número de cuenta',
        ];
    }

    /**
     * @var Customers $customer
     */
    public function map($customer): array
    {
        return [
            $customer->nombre,
            $customer->descripcion,
            $customer->responsable, $customer->correo,
            $customer->celular,
            $customer->banco,
            $customer->titular,
            $customer->clabe,
            $customer->numero_cuenta,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle('Centros Educativos', false);
            },
        ];
    }
}
