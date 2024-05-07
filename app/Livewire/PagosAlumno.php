<?php

namespace App\Livewire;

use App\Models\AlumnoGrupo;
use App\Models\Alumnos;
use App\Models\ConceptosPago;
use App\Models\Customers;
use App\Models\Pagos;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Str;
use PDF;

class PagosAlumno extends Component
{
    use WithPagination;

    protected $listeners = ['goOn-Delete' => 'deleteConceptoPagoAlumno'];

    protected $validationAttributes = [
        'folio_alumno' => 'folio',
        'concepto_pago_alumno' => 'concepto de pago',
        'fecha_vencimiento_alumno' => 'fecha de vencimiento',
        'cargo_alumno' => 'cargo',
    ];

    public $curpAlumno, $nombreAlumno, $correoAlumno, $telefonoAlumno, $folio_alumno_actual, $folio_alumno, $concepto_pago_alumno = null, $fecha_vencimiento_alumno, $cargo_alumno, $list_conceptos_pago, $list_alumnos_grupo, $concepto_pago_alumno_seleccionado, $cursos, $fecha_actual;

    public function printPaymentSchedule()
    {
        // Obtener los datos de la base de datos
        $pagos_alumno = Pagos::query()->where('cancelled', 0)->where('customer_id', auth()->user()->customer_id)->where('curp', $this->curpAlumno)->orderBy('fecha_vencimiento', 'desc')->get();

        $customer = Customers::where('id', auth()->user()->customer_id)->where('cancelled', 0)->first();

        // Arreglo de datos para pasar a la vista
        $data = [
            'title' => __('Payment schedule'),
            'date' => date('d/m/Y'),
            'address' => $customer->descripcion,
            'telephone' => $customer->celular,
            'pagos_alumno' => $pagos_alumno,
            'curp_alumno' => $this->curpAlumno,
            'nombre_alumno' => $this->nombreAlumno,
            'fecha_actual' => date('Y-m-d'),
        ];

        // Cargar la vista PDF y pasarle los datos
        $pdf = PDF::loadView('pdf/calendarioPagosPDF', $data);

        // Descargar el PDF generado
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Calendario de pagos - ' . $this->nombreAlumno . ' (' . $this->curpAlumno . ').pdf');
    }

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $id = Route::current()->parameter('id');
            if (!config('app.debug')) {
                $id = decrypt($id);
            }

            $alumno = Alumnos::where('cancelled', 0)->where('id', $id)->first();
            if ($alumno) {
                $this->curpAlumno = $alumno->curp;
                $this->nombreAlumno = $alumno->nombre . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno;
                $this->correoAlumno = $alumno->correo;
                $this->telefonoAlumno = $alumno->telefono_alumno !== '' || $alumno->telefono_alumno !== null ? $alumno->telefono_alumno : $alumno->telefono_emergencia;
                $this->concepto_pago_alumno_seleccionado = $alumno->curp;
                $this->list_conceptos_pago = ConceptosPago::where('cancelled', 0)->orderBy('nombre', 'asc')->get();
                $this->list_alumnos_grupo = Alumnos::where('cancelled', 0)->where('status', '!=', 2)->where('curp', '!=', $alumno->curp)->orderBy('nombre', 'asc')->get();

                $alumnoGrupo = new AlumnoGrupo();
                $cursos_alumno = $alumnoGrupo->alumnoGrupoCurso($alumno->curp);
                $this->cursos = $cursos_alumno;

                $this->fecha_actual = date('Y-m-d');

                if ($this->curpAlumno == '') {
                    return redirect()->route('login');
                }
            } else {
                return redirect()->route('login');
            }
        }
    }

    public function updatedConceptoPagoAlumno($value)
    {
        $alumnoGrupo = new AlumnoGrupo();
        $cursos_alumno = $alumnoGrupo->alumnoGrupoCurso($this->curpAlumno);

        if ($cursos_alumno->isNotEmpty()) {
            foreach ($cursos_alumno as $alumnoGrupo) {
                if ((__('Inscription') . ' - ' . $alumnoGrupo->nombre) == $value) {
                    $this->cargo_alumno = $alumnoGrupo->inscripcion;
                    $this->fecha_vencimiento_alumno = $alumnoGrupo->fecha_corte;
                } elseif ((__('Course') . ' - ' . $alumnoGrupo->nombre) == $value) {
                    $this->cargo_alumno = $alumnoGrupo->precio_total;
                    $this->fecha_vencimiento_alumno = $alumnoGrupo->fecha_corte;
                } else {
                    $this->cargo_alumno = '';
                    $this->fecha_vencimiento_alumno = '';
                }
            }
        }
    }

    public function updatedFolioAlumno($value)
    {
        $this->concepto_pago_alumno = '';
        $this->fecha_vencimiento_alumno = '';
        $this->cargo_alumno = '';
        do {
            $this->folio_alumno = strtoupper(Str::random(6));
            $conceptoPagoAlumno = Pagos::where('folio', $this->folio_alumno)->first();
        } while ($conceptoPagoAlumno !== null);
    }

    public function updatedFolioAlumnoActual($value)
    {
        $this->folio_alumno_actual = config('app.debug') ? $value : decrypt($value);

        $conceptoPagoAlumno = Pagos::where('folio', $this->folio_alumno_actual)->first();

        $this->concepto_pago_alumno = $conceptoPagoAlumno->concepto;
        $this->fecha_vencimiento_alumno = date('Y-m-d', strtotime($conceptoPagoAlumno->fecha_vencimiento));
        $this->cargo_alumno = $conceptoPagoAlumno->cargo;

        $this->dispatch('selectConceptoPagoAlumnoModal', $this->concepto_pago_alumno);
    }

    #[On('goOn-Delete')]
    public function deleteConceptoPagoAlumno($conceptoPagoAlumnoID)
    {
        $conceptoPagoAlumno = Pagos::find(config('app.debug') ? $conceptoPagoAlumnoID : decrypt($conceptoPagoAlumnoID));
        if ($conceptoPagoAlumno) {
            $conceptoPagoAlumno->cancelled = 1;
            $conceptoPagoAlumno->save();
        }
    }

    public function render()
    {
        $listConceptoPagoAlumno = Pagos::query()->where('cancelled', 0)->where('customer_id', auth()->user()->customer_id)->where('curp', $this->curpAlumno)->orderBy('fecha_vencimiento', 'desc')->paginate(10);

        return view('livewire.pagos-alumno', [
            'list_concepto_pago_alumno' => $listConceptoPagoAlumno,
        ]);
    }

    public function createConceptoPagoAlumno()
    {
        $rules = [
            'folio_alumno' => ['required', 'string', 'max:255'],
            'concepto_pago_alumno' => ['required', 'string', 'max:255'],
            'fecha_vencimiento_alumno' => ['required'],
            'cargo_alumno' => ['required', 'numeric', 'between:0,9999999.99'],
        ];

        $this->validate($rules);

        $concepto_pago_alumno = Pagos::create([
            'folio' => trim($this->folio_alumno),
            'curp' => trim($this->curpAlumno),
            'fecha_vencimiento' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_vencimiento_alumno))),
            'concepto' => trim($this->concepto_pago_alumno),
            'cargo' => trim($this->cargo_alumno),
            'estado_pago' => 0,
            'usuario_responsable' => auth()->user()->id,
            'customer_id' => auth()->user()->customer_id,
            'cancelled' => 0,
        ]);

        if ($concepto_pago_alumno) {
            $this->reset(['folio_alumno', 'concepto_pago_alumno', 'fecha_vencimiento_alumno', 'cargo_alumno']);

            $this->dispatch('pagoCreated');
            $this->dispatch('close-create-concepto-pago-alumno-modal');
        }
    }

    public function editConceptoPagoAlumno()
    {
        $concepto_pago_alumno = Pagos::where('folio', $this->folio_alumno_actual)->first();

        $rules = [
            'folio_alumno_actual' => ['required', 'string', 'max:255'],
            'concepto_pago_alumno' => ['required', 'string', 'max:255'],
            'fecha_vencimiento_alumno' => ['required'],
            'cargo_alumno' => ['required', 'numeric', 'between:0,9999999.99'],
        ];

        $this->validate($rules);

        $concepto_pago_alumno->update([
            'folio_alumno_actual' => trim($this->folio_alumno),
            'curp' => trim($this->concepto_pago_alumno_seleccionado) != null ? trim($this->concepto_pago_alumno_seleccionado) : trim($this->curpAlumno),
            'fecha_vencimiento' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_vencimiento_alumno))),
            'concepto' => trim($this->concepto_pago_alumno),
            'cargo' => trim($this->cargo_alumno),
            'estado_pago' => 0,
            'usuario_responsable' => auth()->user()->id,
            'cancelled' => 0,
        ]);

        if ($concepto_pago_alumno) {
            $this->reset(['folio_alumno_actual', 'concepto_pago_alumno', 'fecha_vencimiento_alumno', 'cargo_alumno']);

            $this->dispatch('pagoUpdated');
            $this->dispatch('close-edit-concepto-pago-alumno-modal');
        }
    }
}
