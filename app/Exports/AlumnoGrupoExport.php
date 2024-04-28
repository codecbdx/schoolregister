<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Alumnos;
use App\Models\Cursos;
use App\Models\Grupos;
use App\Models\AlumnoGrupo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AlumnoGrupoExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $grupo_id;

    public function __construct($grupo_id)
    {
        $this->grupo_id = $grupo_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AlumnoGrupo::where('cancelled', 0)->where('grupo_id', $this->grupo_id)->get();
    }

    public function headings(): array
    {
        return [
            'Grupo',
            'Ciclo',
            'Curso',
            'Modalidad',
            'Código de Moodle',
            'CURP del Alumno',
            'Nombre del alumno',
            'Usuario Moodle',
            'Contraseña Moodle',
            'Fecha de Inscripción',
        ];
    }

    /**
     * @var AlumnoGrupo $alumnoGrupo
     */
    public function map($alumnoGrupo): array
    {
        return [
            $this->getGroupName($alumnoGrupo->grupo_id),
            $this->getGroupCycle($alumnoGrupo->grupo_id),
            $this->getCourseName($alumnoGrupo->grupo_id),
            $this->getGroupModality($alumnoGrupo->grupo_id),
            $this->getCourseMoodleCode($alumnoGrupo->grupo_id),
            $alumnoGrupo->curp,
            $this->getStudentName($alumnoGrupo->curp),
            $this->getStudentUserMoodle($alumnoGrupo->curp),
            $this->getStudentPasswordMoodle($alumnoGrupo->curp),
            Carbon::parse($alumnoGrupo->created_at)->format('d/m/Y'),
        ];
    }

    /**
     * @param int $grupoId
     * @return string
     */
    private function getGroupName($grupoId): string
    {
        $grupo = Grupos::find($grupoId);

        return $grupo ? $grupo->grupo : '';
    }

    /**
     * @param int $grupoId
     * @return string
     */
    private function getGroupCycle($grupoId): string
    {
        $grupo = Grupos::find($grupoId);

        return $grupo ? $grupo->ciclo : '';
    }

    /**
     * @param int $grupoId
     * @return string
     */
    private function getGroupModality($grupoId): string
    {
        $grupo = Grupos::find($grupoId);

        return $grupo ? $grupo->modalidad : '';
    }

    /**
     * @param int $grupoId
     * @return string
     */
    private function getCourseName($grupoId): string
    {
        $grupo = Grupos::find($grupoId);

        $curso = Cursos::find($grupo->curso_id);

        return $curso ? $curso->nombre : '';
    }

    /**
     * @param int $grupoId
     * @return string
     */
    private function getCourseMoodleCode($grupoId): string
    {
        $grupo = Grupos::find($grupoId);

        $curso = Cursos::find($grupo->curso_id);

        return $curso ? $curso->codigo_moodle : '';
    }

    /**
     * @param int $curpId
     * @return string
     */
    private function getStudentName($curpId): string
    {
        $alumno = Alumnos::where('curp', $curpId)->first();

        return $alumno ? $alumno->nombre . ' ' . $alumno->apellido_paterno . ' ' . $alumno->apellido_materno : '';
    }

    /**
     * @param int $curpId
     * @return string
     */
    private function getStudentUserMoodle($curpId): string
    {
        $alumno = Alumnos::where('curp', $curpId)->first();

        return $alumno ? $alumno->usuario_moodle : '';
    }

    /**
     * @param int $curpId
     * @return string
     */
    private function getStudentPasswordMoodle($curpId): string
    {
        $alumno = Alumnos::where('curp', $curpId)->first();

        return $alumno ? $alumno->contrasena_moodle : '';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setTitle('Lista de Alumnos', false);
            },
        ];
    }
}
