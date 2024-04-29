<?php

namespace App\Livewire;

use App\Exports\AlumnosExport;
use App\Models\Customers;
use App\Models\MediosComunicacion;
use App\Models\Parentescos;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Alumnos;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PDF;

class ListAlumnos extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'fecha_nacimiento' => 'fecha de nacimiento',
        'curp' => 'CURP',
        'telefono_emergencia' => 'teléfono de emergencia',
        'telefono_alumno' => 'teléfono de alumno',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'nombre_tutor' => 'nombre de tutor',
        'apellido_paterno_tutor' => 'apellido paterno de tutor',
        'apellido_materno_tutor' => 'apellido materno de tutor',
        'telefono_tutor' => 'teléfono de tutor',
        'parentesco_tutor' => 'parentesco de tutor',
        'medio_interaccion' => '¿como nos conociste?',
    ];

    protected $listeners = ['goOn-Delete-Alumno' => 'deleteAlumno', 'goOn-Print-Credential-Alumno' => 'printCredentialAlumno'];

    public $sortField = 'created_at', $sortAsc = true, $search = '', $totalEntries, $nombre, $apellido_paterno, $apellido_materno, $curp, $sexo, $fecha_nacimiento, $correo, $telefono_emergencia, $telefono_alumno, $facebook, $instagram, $nombre_tutor, $apellido_paterno_tutor, $apellido_materno_tutor, $parentesco_tutor, $telefono_tutor, $medio_interaccion, $medios, $parentescos, $selectedStatus = null, $modulePermissions, $filtro_fecha_inscripcion;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Alumnos::totalCount(auth()->user()->customer_id);
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
            $this->medios = MediosComunicacion::where('cancelled', 0)->get();
            $this->parentescos = Parentescos::where('cancelled', 0)->get();
        }
    }

    public function export()
    {
        return Excel::download(new AlumnosExport($this->filtro_fecha_inscripcion, $this->selectedStatus), 'Alumnos.xlsx');
    }

    public function updatedCurp($value)
    {
        // Define la expresión regular para la CURP
        $pattern = '/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/';

        // Primero, verifica si la CURP tiene la longitud correcta y cumple con la expresión regular
        if (strlen($value) == 18 && preg_match($pattern, $value)) {
            // Verifica si la CURP ya está registrada
            $curpExistente = Alumnos::where('curp', $value)->first();
            if ($curpExistente) {
                // Si la CURP ya existe y no pertenece al alumno actual, agrega un error
                $this->sexo = '';
                $this->fecha_nacimiento = '';
                $this->addError('curp', 'La CURP ingresada ya está registrada.');
            } else {
                // Si la CURP no está registrada, limpia los errores y extrae los datos
                $this->resetErrorBag('curp');
                $this->extraerDatosDeCURP($value);
            }
        } else {
            // Si la CURP no cumple con la longitud o el patrón, agrega un error
            $this->sexo = '';
            $this->fecha_nacimiento = '';
            $this->addError('curp', 'La CURP ingresada no es válida.');
        }
    }

    protected function extraerDatosDeCURP($curp)
    {
        // Extraer sexo
        $sexo = substr($curp, 10, 1);
        $this->sexo = $sexo == 'H' ? 'Hombre' : 'Mujer';

        // Extraer fecha de nacimiento
        $ano = substr($curp, 4, 2);
        $mes = substr($curp, 6, 2);
        $dia = substr($curp, 8, 2);
        // Asumiendo que el CURP se emite para personas nacidas después de 1900
        $anoCompleto = $ano > date('y') ? '19' . $ano : '20' . $ano;
        $this->fecha_nacimiento = $dia . '-' . $mes . '-' . $anoCompleto;
        $this->fecha_nacimiento = Carbon::createFromFormat('d-m-Y', $this->fecha_nacimiento)->format('Y-m-d');
    }

    public function createAlumno()
    {
        $messages = [
            'curp.unique' => 'Esta CURP ya está registrada.',
            'correo.unique' => 'Este correo eléctronico ya está registrado.',
        ];

        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'curp' => [
                'required',
                'string',
                'max:18',
                'unique:alumnos,curp',
                'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'
            ],
            'sexo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:alumnos,correo', 'unique:users,email'],
            'telefono_emergencia' => ['nullable', 'string', 'max:255', 'digits_between:7,15'],
            'telefono_alumno' => ['required', 'string', 'max:255', 'digits_between:7,15'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'nombre_tutor' => ['nullable', 'string', 'max:255'],
            'apellido_paterno_tutor' => ['nullable', 'string', 'max:255'],
            'apellido_materno_tutor' => ['nullable', 'string', 'max:255'],
            'telefono_tutor' => ['nullable', 'string', 'max:255', 'digits_between:7,15'],
            'parentesco_tutor' => ['nullable', 'string', 'max:255'],
            'medio_interaccion' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules, $messages);

        $user = User::create([
            'name' => trim($this->nombre),
            'paternal_lastname' => trim($this->apellido_paterno),
            'maternal_lastname' => trim($this->apellido_materno),
            'email' => trim($this->correo),
            'password' => Hash::make($this->curp),
            'customer_id' => auth()->user()->customer_id,
            'user_type_id' => 4,
            'user_image' => 'photos/user.png',
            'cancelled' => 0,
        ]);

        if ($user) {
            $alumno = Alumnos::create([
                'nombre' => trim($this->nombre),
                'apellido_paterno' => trim($this->apellido_paterno),
                'apellido_materno' => trim($this->apellido_materno),
                'curp' => trim($this->curp),
                'sexo' => trim($this->sexo),
                'fecha_nacimiento' => date('Y-m-d', strtotime(str_replace('-', '/', $this->fecha_nacimiento))),
                'correo' => trim($this->correo),
                'telefono_emergencia' => trim($this->telefono_emergencia),
                'telefono_alumno' => trim($this->telefono_alumno),
                'facebook' => trim($this->facebook),
                'instagram' => trim($this->instagram),
                'nombre_tutor' => trim($this->nombre_tutor),
                'apellido_paterno_tutor' => trim($this->apellido_paterno_tutor),
                'apellido_materno_tutor' => trim($this->apellido_materno_tutor),
                'parentesco_tutor' => trim($this->parentesco_tutor),
                'telefono_tutor' => trim($this->telefono_tutor),
                'medio_interaccion' => trim($this->medio_interaccion),
                'usuario_moodle' => trim(substr($this->curp, 0, 10) . substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3 / strlen('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')))), 1, 3)),
                'contrasena_moodle' => trim(strtoupper(substr($this->curp, 0, 4) . substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(6 / strlen('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')))), 1, 6))),
                'status' => 2,
                'customer_id' => auth()->user()->customer_id,
                'cancelled' => 0,
            ]);

            if ($alumno) {
                $this->reset(['nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'sexo', 'fecha_nacimiento', 'correo', 'facebook', 'instagram', 'nombre_tutor', 'apellido_paterno_tutor', 'apellido_materno_tutor', 'parentesco_tutor', 'telefono_emergencia', 'telefono_tutor', 'telefono_alumno', 'medio_interaccion', 'correo']);

                $this->dispatch('alumnoCreated');
                $this->dispatch('close-create-alumno-modal');
            } else {
                $user->delete();
            }
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function filterByStatus($statusID = null)
    {
        $this->selectedStatus = $statusID;
        $this->search();
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    #[On('goOn-Delete-Alumno')]
    public function deleteAlumno($alumnoId)
    {
        $alumno = Alumnos::find(config('app.debug') ? $alumnoId : decrypt($alumnoId));
        if ($alumno) {
            $alumno->cancelled = 1;
            $alumno->save();
        }
    }

    public function updatedFiltroFechaInscripcion($value)
    {
        if ($value) {
            $this->filtro_fecha_inscripcion = Carbon::parse($value)->startOfDay(); // Truncar la hora
        } else {
            $this->filtro_fecha_inscripcion = null;
        }

        $this->search();

        $this->filtro_fecha_inscripcion = $value;
    }

    public function render()
    {
        $query = Alumnos::search(trim($this->search), auth()->user()->customer_id)
            ->when($this->selectedStatus !== null, function ($query) {
                // Asegúrate de que la condición maneje correctamente el valor 0.
                $query->where('status', $this->selectedStatus);
            });

        // Aplicar filtro de fecha si está definido y no es nulo ni vacío
        if (!empty($this->filtro_fecha_inscripcion)) {
            $query->whereDate('created_at', $this->filtro_fecha_inscripcion);
        }

        $query->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        return view('livewire.list-alumnos', [
            'listAlumnos' => $query->paginate(10),
        ]);
    }

    #[On('goOn-Print-Credential-Alumno')]
    public function printCredentialAlumno($alumnoId)
    {
        // Obtener los datos de la base de datos
        $alumno = Alumnos::query()->where('cancelled', 0)->where('id', config('app.debug') ? $alumnoId : decrypt($alumnoId))->first();

        $user = User::where('email', $alumno->correo)->where('cancelled', 0)->first();

        $customer = Customers::where('id', auth()->user()->customer_id)->where('cancelled', 0)->first();

        // Arreglo de datos para pasar a la vista
        $data = [
            'title' => __('Credential'),
            'date' => date('d/m/Y', strtotime('+1 year', strtotime(str_replace('-', '/', $alumno->created_at)))),
            'address' => $customer->descripcion,
            'telephone' => $customer->celular,
            'alumno' => $alumno,
            'usuario' => $user,
        ];

        // Cargar la vista PDF y pasarle los datos
        $pdf = PDF::loadView('pdf/credencialAlumnoPDF', $data);

        // Descargar el PDF generado
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, __('Credential') . ' - ' . $alumno->nombre . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno . ' (' . $alumno->curp . ').pdf');
    }
}
