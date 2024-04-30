<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Alumnos;
use App\Models\Pagos;
use Livewire\Component;

class Inicio extends Component
{
    public $list_conceptos_pago_alumnos, $students, $studentData, $adminSection = false;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            if (auth()->user()->user_type_id === 1) {
                $this->list_conceptos_pago_alumnos = Pagos::where('cancelled', 0)
                    ->where('customer_id', auth()->user()->customer_id)
                    ->orderBy('fecha_vencimiento', 'desc')
                    ->get();

                $this->students = Alumnos::where('cancelled', 0)
                    ->where('customer_id', auth()->user()->customer_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                $studentsPerMonthYear = $this->students->groupBy(function ($student) {
                    $year = $student->created_at->format('Y');
                    $month = $student->created_at->format('m');
                    $monthName = Carbon::createFromFormat('m', $month)->locale('es')->monthName;
                    return "$monthName $year";
                })->map(function ($group) {
                    return $group->count();
                });

                // Crear la estructura de datos para enviar a la vista
                $this->studentData = $studentsPerMonthYear->map(function ($count, $monthYear) {
                    return [
                        'month_year' => ucfirst($monthYear),
                        'student_count' => $count,
                    ];
                })->values()->toArray();

                $this->adminSection = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.inicio');
    }
}
