<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Customers;
use App\Models\UserTypes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class UsuariosExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $selectedUserTypeId;

    public function __construct($selectedUserTypeId)
    {
        $this->selectedUserTypeId = $selectedUserTypeId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = User::where('cancelled', 0)
            ->where('customer_id', auth()->user()->customer_id)
            ->where('user_type_id', '!=', 4);

        if ($this->selectedUserTypeId !== null) {
            $query->where('user_type_id', $this->selectedUserTypeId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Fecha de creación',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Correo electrónico',
            'Centro educativo',
            'Rol de usuario',
        ];
    }

    /**
     * @var User $usuario
     */
    public function map($usuario): array
    {
        return [
            Carbon::parse($usuario->created_at)->format('d/m/Y'),
            $usuario->name,
            $usuario->paternal_lastname,
            $usuario->maternal_lastname,
            $usuario->email,
            $this->getCustomerName($usuario->customer_id), // Obtener el nombre del centro educativo
            $this->getUserTypeName($usuario->user_type_id), // Obtener el nombre del rol
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
     * Obtiene el nombre del tipo de usuario dado un user_type_id
     *
     * @param int $userTypeId
     * @return string
     */
    private function getUserTypeName($userTypeId): string
    {
        $userType = UserTypes::find($userTypeId);

        return $userType ? $userType->name : '';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle('Usuario', false);
            },
        ];
    }
}
