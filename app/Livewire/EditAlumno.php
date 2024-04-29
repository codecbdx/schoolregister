<?php

namespace App\Livewire;

use App\Models\Parentescos;
use App\Models\User;
use App\Services\DocumentacionCompletaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\Alumnos;
use App\Models\MediosComunicacion;

class EditAlumno extends Component
{
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

    public $decryptedId, $nombre, $apellido_paterno, $apellido_materno, $curp, $sexo, $fecha_nacimiento, $correo, $telefono_emergencia, $telefono_alumno, $facebook, $instagram, $nombre_tutor, $apellido_paterno_tutor, $apellido_materno_tutor, $parentesco_tutor, $telefono_tutor, $medio_interaccion, $medios, $parentescos, $status, $user_moodle, $password_moodle, $correo_sistema;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->medios = MediosComunicacion::where('cancelled', 0)->get();
            $this->parentescos = Parentescos::where('cancelled', 0)->get();

            $this->decryptedId = config('app.debug') ? $id : decrypt($id);

            $alumno = Alumnos::find($this->decryptedId);
            $this->nombre = $alumno->nombre;
            $this->apellido_paterno = $alumno->apellido_paterno;
            $this->apellido_materno = $alumno->apellido_materno;
            $this->curp = $alumno->curp;
            $this->sexo = $alumno->sexo;
            $this->fecha_nacimiento = $alumno->fecha_nacimiento;
            $this->correo = $alumno->correo;
            $this->telefono_emergencia = $alumno->telefono_emergencia;
            $this->telefono_alumno = $alumno->telefono_alumno;
            $this->facebook = $alumno->facebook;
            $this->instagram = $alumno->instagram;
            $this->nombre_tutor = $alumno->nombre_tutor;
            $this->apellido_paterno_tutor = $alumno->apellido_paterno_tutor;
            $this->apellido_materno_tutor = $alumno->apellido_materno_tutor;
            $this->parentesco_tutor = $alumno->parentesco_tutor;
            $this->telefono_tutor = $alumno->telefono_tutor;
            $this->medio_interaccion = $alumno->medio_interaccion;
            $this->status = $alumno->status;
            $this->user_moodle = $alumno->usuario_moodle;
            $this->password_moodle = $alumno->contrasena_moodle;
            $this->correo_sistema = $alumno->correo;
        }
    }

    public function updatedStatus()
    {
        // Asegurarse de que se está trabajando con el alumno correcto
        $alumno = Alumnos::find($this->decryptedId);

        if ($alumno) {
            // Verificar la completitud de los documentos antes de actualizar el estado
            if (app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($alumno->curp)) {
                // Si la documentación está completa, actualiza el estado basado en $this->status
                $alumno->status = $this->status ? 1 : 0;
                $alumno->save();
                $this->status = $this->status ? 1 : 0;
            }
        }
    }

    public function updatedCurp($value)
    {
        // Define la expresión regular para la CURP
        $pattern = '/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/';

        // Primero, verifica si la CURP tiene la longitud correcta y cumple con la expresión regular
        if (strlen($value) == 18 && preg_match($pattern, $value)) {
            $alumno = Alumnos::find($this->decryptedId);
            // Verifica si la CURP ya está registrada
            $curpExistente = Alumnos::where('curp', $value)->first();
            if ($curpExistente && $curpExistente->curp != $alumno->curp) {
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

    public function render()
    {
        return view('livewire.edit-alumno');
    }

    public function save()
    {
        $alumno = Alumnos::find($this->decryptedId);

        $messages = [
            'curp.unique' => 'Esta CURP ya está registrada.',
            'correo.unique' => 'Este correo eléctronico ya está registrado.',
        ];

        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'sexo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required'],
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

        if ($this->correo !== $alumno->correo) {
            $rules['correo'] = 'required|string|email|max:255|unique:alumnos,correo|unique:users,email';
        } else {
            $rules['correo'] = 'required|string|email|max:255';
        }

        if ($this->curp !== $alumno->curp) {
            $rules['curp'] = [
                'required',
                'string',
                'max:255',
                'unique:alumnos,curp',
                'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'
            ];
        } else {
            $rules['curp'] = [
                'required',
                'string',
                'max:255',
                'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'
            ];
        }

        $this->validate($rules, $messages);

        $user = User::where('email', $alumno->correo)->first();
        if ($user) {
            $user->update([
                'email' => trim($this->correo),
            ]);
            $user->save();
        } else {
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
        }

        if ($user) {
            $alumno->update([
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
            ]);

            if (!app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curp)) {
                $alumno->status = 2;
                $alumno->save();

                return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
            } elseif ($this->status == 2) {
                $alumno->status = 0;
                $alumno->save();

                return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
            }

            $this->dispatch('alumnoUpdated');
        }
    }
}
