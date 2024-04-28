<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use App\Models\DocumentosAlumno;
use App\Models\DireccionesAlumno;
use App\Models\UniversidadesAlumno;
use App\Models\EducacionMediaSuperior;

class DocumentacionCompletaService
{
    /**
     * Verifica si la documentación de un alumno está completa basándose en su CURP.
     *
     * @param string $curp CURP del alumno.
     * @return bool Retorna true si la documentación está completa, false en caso contrario.
     */
    public function verificarDocumentacionCompleta($curp)
    {
        try {
            return $this->tieneDocumentos($curp) &&
                $this->tieneDireccion($curp) &&
                $this->tieneUniversidad($curp) &&
                $this->tieneEducacionMediaSuperior($curp);
        } catch (QueryException $e) {
            // Log the error or handle it as necessary
            // Log::error("Error verifying documentation completeness: " . $e->getMessage());
            return false;
        }
    }

    protected function calcularEdadDesdeCURP($curp)
    {
        $ano = substr($curp, 4, 2);
        $mes = substr($curp, 6, 2);
        $dia = substr($curp, 8, 2);
        $anoCompleto = $ano > date('y') ? '19' . $ano : '20' . $ano;
        $fechaNacimiento = Carbon::createFromFormat('Y-m-d', $anoCompleto . '-' . $mes . '-' . $dia);

        return $fechaNacimiento->age;
    }

    public function tieneDocumentos($curp)
    {
        $edad = $this->calcularEdadDesdeCURP($curp);

        if ($edad >= 18) {
            // Verifica que cada uno de los documentos requeridos exista
            $tieneINE = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'ine_alumno')
                ->where('cancelled', 0)
                ->exists();

            $tieneCURP = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'curp_alumno')
                ->where('cancelled', 0)
                ->exists();

            $tieneComprobanteDomicilio = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'comprobante_domicilio_alumno')
                ->where('cancelled', 0)
                ->exists();

            // Retorna true solo si todos los documentos existen
            return $tieneINE && $tieneCURP && $tieneComprobanteDomicilio;
        } else {
            // Verifica que cada uno de los documentos requeridos exista para un menor de edad
            $tieneIneTutor = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'ine_tutor')
                ->where('cancelled', 0)
                ->exists();

            $tieneCurpTutor = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'curp_tutor')
                ->where('cancelled', 0)
                ->exists();

            $tieneCurpAlumno = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'curp_alumno')
                ->where('cancelled', 0)
                ->exists();

            $tieneComprobanteDomicilio = DocumentosAlumno::where('curp', $curp)
                ->where('tipo_documento', 'comprobante_domicilio_alumno')
                ->where('cancelled', 0)
                ->exists();

            // Retorna true solo si todos los documentos existen
            return $tieneIneTutor && $tieneCurpTutor && $tieneCurpAlumno && $tieneComprobanteDomicilio;
        }
    }

    protected function tieneDireccion($curp)
    {
        return DireccionesAlumno::where('curp', $curp)->where('cancelled', 0)->exists();
    }

    protected function tieneUniversidad($curp)
    {
        return UniversidadesAlumno::where('curp', $curp)->where('cancelled', 0)->exists();
    }

    protected function tieneEducacionMediaSuperior($curp)
    {
        return EducacionMediaSuperior::where('curp', $curp)->where('cancelled', 0)->exists();
    }
}
