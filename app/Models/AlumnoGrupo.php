<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoGrupo extends Model
{
    use HasFactory;

    protected $table = 'alumno_grupo';

    protected $fillable = ['curp', 'grupo_id'];

    public function scopeCurpsGrupo($query, $grupo_id)
    {
        return $query->join('alumnos', 'alumnos.curp', '=', 'alumno_grupo.curp')
            ->leftJoin('grupos', 'grupos.id', '=', 'alumno_grupo.grupo_id')
            ->leftJoin('cursos', 'cursos.id', '=', 'grupos.curso_id')
            ->select('alumno_grupo.*', 'alumnos.nombre', 'alumnos.apellido_paterno', 'alumnos.apellido_materno', 'alumnos.usuario_moodle', 'alumnos.contrasena_moodle', 'cursos.nombre as curso', 'cursos.codigo_moodle')
            ->where('alumno_grupo.grupo_id', $grupo_id)
            ->where('alumno_grupo.cancelled', 0)
            ->where('alumnos.cancelled', 0)
            ->where('alumnos.status', '!=', 2)
            ->whereIn('grupos.cancelled', [0, 2])
            ->where('cursos.cancelled', 0)
            ->orderBy('alumno_grupo.created_at', 'desc')
            ->get()
            ->pluck('curp');
    }

    public function alumnosGrupo($grupo_id)
    {
        return $this->join('alumnos', 'alumnos.curp', '=', 'alumno_grupo.curp')
            ->leftJoin('grupos', 'grupos.id', '=', 'alumno_grupo.grupo_id')
            ->leftJoin('cursos', 'cursos.id', '=', 'grupos.curso_id')
            ->select('alumno_grupo.*', 'alumnos.nombre', 'alumnos.apellido_paterno', 'alumnos.apellido_materno', 'alumnos.usuario_moodle', 'alumnos.contrasena_moodle', 'cursos.nombre as curso', 'cursos.codigo_moodle')
            ->where('alumno_grupo.grupo_id', $grupo_id)
            ->where('alumno_grupo.cancelled', 0)
            ->where('alumnos.cancelled', 0)
            ->where('alumnos.status', '!=', 2)
            ->whereIn('grupos.cancelled', [0, 2])
            ->where('cursos.cancelled', 0)
            ->orderBy('alumno_grupo.created_at', 'desc');
    }

    public function alumnosGrupoCurso($grupo_id)
    {
        return $this->join('alumnos', 'alumnos.curp', '=', 'alumno_grupo.curp')
            ->leftJoin('grupos', 'grupos.id', '=', 'alumno_grupo.grupo_id')
            ->leftJoin('cursos', 'cursos.id', '=', 'grupos.curso_id')
            ->select('alumno_grupo.*', 'alumnos.nombre', 'alumnos.apellido_paterno', 'alumnos.apellido_materno', 'alumnos.nombre_tutor', 'alumnos.apellido_paterno_tutor', 'alumnos.apellido_materno_tutor', 'alumnos.correo', 'alumnos.telefono_emergencia', 'alumnos.created_at', 'cursos.nombre as nombre_curso', 'cursos.codigo_moodle')
            ->where('alumno_grupo.grupo_id', $grupo_id)
            ->where('alumno_grupo.cancelled', 0)
            ->where('alumnos.cancelled', 0)
            ->where('alumnos.status', '!=', 2)
            ->whereIn('grupos.cancelled', [0, 2])
            ->where('cursos.cancelled', 0)
            ->orderBy('alumnos.nombre', 'asc')
            ->get();
    }

    public function alumnoGrupoCurso($CURP)
    {
        return $this
            ->select('cursos.nombre', 'grupos.fecha_corte', 'grupos.inscripcion', 'grupos.precio_total')
            ->leftJoin('grupos', 'grupos.id', '=', 'alumno_grupo.grupo_id')
            ->leftJoin('cursos', 'cursos.id', '=', 'grupos.curso_id')
            ->where('alumno_grupo.curp', $CURP)
            ->where('alumno_grupo.cancelled', 0)
            ->whereIn('grupos.cancelled', [0, 2])
            ->where('cursos.cancelled', 0)
            ->orderBy('cursos.nombre', 'asc')
            ->get();
    }
}
