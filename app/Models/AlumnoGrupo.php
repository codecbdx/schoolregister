<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoGrupo extends Model
{
    use HasFactory;

    protected $table = 'alumno_grupo';

    protected $fillable = ['curp', 'grupo_id'];
}
