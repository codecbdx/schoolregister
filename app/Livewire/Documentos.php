<?php

namespace App\Livewire;

use App\Models\Alumnos;
use App\Services\DocumentacionCompletaService;
use Carbon\Carbon;
use App\Models\DocumentosAlumno;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\S3Service;

class Documentos extends Component
{
    use WithFileUploads;

    protected $s3Service;

    public $ine_pdf, $ine_tutor_pdf, $curp_pdf, $curp_tutor_pdf, $address_pdf, $current_ine_pdf, $current_ine_tutor_pdf, $current_curp_pdf, $current_curp_tutor_pdf, $current_address_pdf, $curp, $mayor_edad, $userSignedINE, $userSignedINETutor, $userSignedCURP, $userSignedCURPTutor, $userSignedAddress;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->current_ine_pdf = '';
            $this->current_ine_tutor_pdf = '';
            $this->current_curp_pdf = '';
            $this->current_curp_tutor_pdf = '';
            $this->current_address_pdf = '';
        }
    }

    #[On('goOn-Load-PDF')]
    public function loadPDFS()
    {
        $this->s3Service = new S3Service();

        $fechaNacimiento = $this->extraerDatosDeCURP($this->curp);
        $this->mayor_edad = $fechaNacimiento->age >= 18;

        $documentos = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_alumno')
            ->first();

        if ($documentos !== null && $documentos->archivo_pdf !== null) {
            $this->current_ine_pdf = $documentos->archivo_pdf;

            $signedUrl = $this->s3Service->getPreSignedUrl($this->current_ine_pdf, 30);
            $this->userSignedINE = $signedUrl;
        } else {
            $this->current_ine_pdf = '';
        }

        $documentos = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_alumno')
            ->first();

        if ($documentos !== null && $documentos->archivo_pdf !== null) {
            $this->current_curp_pdf = $documentos->archivo_pdf;

            $signedUrl = $this->s3Service->getPreSignedUrl($this->current_curp_pdf, 30);
            $this->userSignedCURP = $signedUrl;
        } else {
            $this->current_curp_pdf = '';
        }

        $documentos = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'comprobante_domicilio_alumno')
            ->first();

        if ($documentos !== null && $documentos->archivo_pdf !== null) {
            $this->current_address_pdf = $documentos->archivo_pdf;

            $signedUrl = $this->s3Service->getPreSignedUrl($this->current_address_pdf, 30);
            $this->userSignedAddress = $signedUrl;
        } else {
            $this->current_address_pdf = '';
        }

        $documentos = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_tutor')
            ->first();

        if ($documentos !== null && $documentos->archivo_pdf !== null) {
            $this->current_ine_tutor_pdf = $documentos->archivo_pdf;

            $signedUrl = $this->s3Service->getPreSignedUrl($this->current_ine_tutor_pdf, 30);
            $this->userSignedINETutor = $signedUrl;
        } else {
            $this->current_ine_tutor_pdf = '';
        }

        $documentos = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_tutor')
            ->first();

        if ($documentos !== null && $documentos->archivo_pdf !== null) {
            $this->current_curp_tutor_pdf = $documentos->archivo_pdf;

            $signedUrl = $this->s3Service->getPreSignedUrl($this->current_curp_tutor_pdf, 30);
            $this->userSignedCURPTutor = $signedUrl;
        } else {
            $this->current_curp_tutor_pdf = '';
        }
    }

    public function updatedInePdf()
    {
        $this->validate([
            'ine_pdf' => 'file|mimes:pdf|max:5120',
        ]);
    }

    public function updatedIneTutorPdf()
    {
        $this->validate([
            'ine_tutor_pdf' => 'file|mimes:pdf|max:5120',
        ]);
    }

    public function updatedCurpPdf()
    {
        $this->validate([
            'curp_pdf' => 'file|mimes:pdf|max:5120',
        ]);
    }

    public function updatedCurpTutorPdf()
    {
        $this->validate([
            'curp_tutor_pdf' => 'file|mimes:pdf|max:5120',
        ]);
    }

    public function updatedAddressPdf()
    {
        $this->validate([
            'address_pdf' => 'file|mimes:pdf|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.documentos');
    }

    public function loadINE()
    {
        $this->s3Service = new S3Service();

        if ($this->ine_pdf) {
            // Generar un nombre de archivo único usando la CURP y un sufijo aleatorio
            $filename = 'INE-Alumno-' . $this->curp . '-' . uniqid() . '.' . $this->ine_pdf->getClientOriginalExtension();

            // Guardar el archivo en el disco especificado con el nombre de archivo personalizado
            $savedFile = $this->ine_pdf->storeAs('documentos-alumno', $filename, 's3');
        } else {
            $savedFile = $this->current_ine_pdf;
        }

        $documento_ine = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_alumno')
            ->first();

        if ($documento_ine !== null && $documento_ine->archivo_pdf !== null) {
            $documento_ine->update([
                'archivo_pdf' => $savedFile,
            ]);
        } else {
            DocumentosAlumno::create([
                'curp' => trim($this->curp),
                'tipo_documento' => 'ine_alumno',
                'archivo_pdf' => trim($savedFile),
                'cancelled' => 0,
            ]);
        }

        $this->ine_pdf = false;
        $this->current_ine_pdf = $savedFile;
        $signedUrl = $this->s3Service->getPreSignedUrl($this->current_ine_pdf, 30);
        $this->userSignedINE = $signedUrl;

        $this->documentacionCorrecta();
    }

    public function deleteINE()
    {
        $documento_ine = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_alumno')
            ->first();

        if ($documento_ine !== null) {
            $documento_ine->update([
                'cancelled' => 1,
            ]);
        }

        $this->current_ine_pdf = '';

        $this->documentacionIncorrecta();
    }

    public function loadCURP()
    {
        $this->s3Service = new S3Service();

        if ($this->curp_pdf) {
            // Generar un nombre de archivo único usando la CURP y un sufijo aleatorio
            $filename = 'CURP-Alumno-' . $this->curp . '-' . uniqid() . '.' . $this->curp_pdf->getClientOriginalExtension();

            // Guardar el archivo en el disco especificado con el nombre de archivo personalizado
            $savedFile = $this->curp_pdf->storeAs('documentos-alumno', $filename, 's3');
        } else {
            $savedFile = $this->current_curp_pdf;
        }

        $documento_curp = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_alumno')
            ->first();

        if ($documento_curp !== null && $documento_curp->archivo_pdf !== null) {
            $documento_curp->update([
                'archivo_pdf' => $savedFile,
            ]);
        } else {
            DocumentosAlumno::create([
                'curp' => trim($this->curp),
                'tipo_documento' => 'curp_alumno',
                'archivo_pdf' => trim($savedFile),
                'cancelled' => 0,
            ]);
        }

        $this->curp_pdf = false;
        $this->current_curp_pdf = $savedFile;
        $signedUrl = $this->s3Service->getPreSignedUrl($this->current_curp_pdf, 30);
        $this->userSignedCURP = $signedUrl;

        $this->documentacionCorrecta();
    }

    public function deleteCURP()
    {
        $documento_curp = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_alumno')
            ->first();

        if ($documento_curp !== null) {
            $documento_curp->update([
                'cancelled' => 1,
            ]);
        }

        $this->current_curp_pdf = '';

        $this->documentacionIncorrecta();
    }

    public function loadComprobante()
    {
        $this->s3Service = new S3Service();

        if ($this->address_pdf) {
            // Generar un nombre de archivo único usando la CURP y un sufijo aleatorio
            $filename = 'Comprobante-Domicilio-Alumno-' . $this->curp . '-' . uniqid() . '.' . $this->address_pdf->getClientOriginalExtension();

            // Guardar el archivo en el disco especificado con el nombre de archivo personalizado
            $savedFile = $this->address_pdf->storeAs('documentos-alumno', $filename, 's3');
        } else {
            $savedFile = $this->current_address_pdf;
        }

        $documento_comprobante = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'comprobante_domicilio_alumno')
            ->first();

        if ($documento_comprobante !== null && $documento_comprobante->archivo_pdf !== null) {
            $documento_comprobante->update([
                'archivo_pdf' => $savedFile,
            ]);
        } else {
            DocumentosAlumno::create([
                'curp' => trim($this->curp),
                'tipo_documento' => 'comprobante_domicilio_alumno',
                'archivo_pdf' => trim($savedFile),
                'cancelled' => 0,
            ]);
        }

        $this->address_pdf = false;
        $this->current_address_pdf = $savedFile;
        $signedUrl = $this->s3Service->getPreSignedUrl($this->current_address_pdf, 30);
        $this->userSignedAddress = $signedUrl;

        $this->documentacionCorrecta();
    }

    public function deleteAddress()
    {
        $documento_comprobante = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'comprobante_domicilio_alumno')
            ->first();

        if ($documento_comprobante !== null) {
            $documento_comprobante->update([
                'cancelled' => 1,
            ]);
        }

        $this->current_address_pdf = '';

        $this->documentacionIncorrecta();
    }

    public function loadINETutor()
    {
        $this->s3Service = new S3Service();

        if ($this->ine_tutor_pdf) {
            // Generar un nombre de archivo único usando la CURP y un sufijo aleatorio
            $filename = 'INE-Tutor-' . $this->curp . '-' . uniqid() . '.' . $this->ine_tutor_pdf->getClientOriginalExtension();

            // Guardar el archivo en el disco especificado con el nombre de archivo personalizado
            $savedFile = $this->ine_tutor_pdf->storeAs('documentos-alumno', $filename, 's3');
        } else {
            $savedFile = $this->current_ine_tutor_pdf;
        }

        $documento_ine_tutor = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_tutor')
            ->first();

        if ($documento_ine_tutor !== null && $documento_ine_tutor->archivo_pdf !== null) {
            $documento_ine_tutor->update([
                'archivo_pdf' => $savedFile,
            ]);
        } else {
            DocumentosAlumno::create([
                'curp' => trim($this->curp),
                'tipo_documento' => 'ine_tutor',
                'archivo_pdf' => trim($savedFile),
                'cancelled' => 0,
            ]);
        }

        $this->ine_tutor_pdf = false;
        $this->current_ine_tutor_pdf = $savedFile;
        $signedUrl = $this->s3Service->getPreSignedUrl($this->current_ine_tutor_pdf, 30);
        $this->userSignedINETutor = $signedUrl;

        $this->documentacionCorrecta();
    }

    public function deleteINETutor()
    {
        $documento_ine_tutor = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'ine_tutor')
            ->first();

        if ($documento_ine_tutor !== null) {
            $documento_ine_tutor->update([
                'cancelled' => 1,
            ]);
        }

        $this->current_ine_tutor_pdf = '';

        $this->documentacionIncorrecta();
    }

    public function loadCURPTutor()
    {
        $this->s3Service = new S3Service();

        if ($this->curp_tutor_pdf) {
            // Generar un nombre de archivo único usando la CURP y un sufijo aleatorio
            $filename = 'CURP-Tutor-' . $this->curp . '-' . uniqid() . '.' . $this->curp_tutor_pdf->getClientOriginalExtension();

            // Guardar el archivo en el disco especificado con el nombre de archivo personalizado
            $savedFile = $this->curp_tutor_pdf->storeAs('documentos-alumno', $filename, 's3');
        } else {
            $savedFile = $this->current_curp_tutor_pdf;
        }

        $documento_curp_tutor = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_tutor')
            ->first();

        if ($documento_curp_tutor !== null && $documento_curp_tutor->archivo_pdf !== null) {
            $documento_curp_tutor->update([
                'archivo_pdf' => $savedFile,
            ]);
        } else {
            DocumentosAlumno::create([
                'curp' => trim($this->curp),
                'tipo_documento' => 'curp_tutor',
                'archivo_pdf' => trim($savedFile),
                'cancelled' => 0,
            ]);
        }

        $this->curp_tutor_pdf = false;
        $this->current_curp_tutor_pdf = $savedFile;
        $signedUrl = $this->s3Service->getPreSignedUrl($this->current_curp_tutor_pdf, 30);
        $this->userSignedCURPTutor = $signedUrl;

        $this->documentacionCorrecta();
    }

    public function deleteCURPTutor()
    {
        $documento_curp_tutor = DocumentosAlumno::where('cancelled', 0)
            ->where('curp', $this->curp)
            ->where('tipo_documento', 'curp_tutor')
            ->first();

        if ($documento_curp_tutor !== null) {
            $documento_curp_tutor->update([
                'cancelled' => 1,
            ]);
        }

        $this->current_curp_tutor_pdf = '';

        $this->documentacionIncorrecta();
    }

    private function documentacionCorrecta()
    {
        if (app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curp)) {
            $alumno = Alumnos::where('curp', $this->curp)->first();

            if ($alumno) {
                $alumno->update(['status' => 0]);

                return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
            }
        }
    }

    private function documentacionIncorrecta()
    {
        if (!app(DocumentacionCompletaService::class)->verificarDocumentacionCompleta($this->curp)) {
            $alumno = Alumnos::where('curp', $this->curp)->first();

            if ($alumno) {
                $alumno->update(['status' => 2]);

                return redirect()->route('edit_student', ['id' => config('app.debug') ? $alumno->id : encrypt($alumno->id)]);
            }
        }
    }

    protected function extraerDatosDeCURP($curp)
    {
        $ano = substr($curp, 4, 2);
        $mes = substr($curp, 6, 2);
        $dia = substr($curp, 8, 2);
        $siglo = $ano >= date('y') ? '19' : '20';

        return Carbon::createFromFormat('Y-m-d', $siglo . $ano . '-' . $mes . '-' . $dia);
    }
}
