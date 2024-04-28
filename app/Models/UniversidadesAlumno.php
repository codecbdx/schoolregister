<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversidadesAlumno extends Model
{
    use HasFactory;

    protected $table = 'educacion_superior';

    protected $fillable = ['curp', 'universidad', 'licenciatura', 'fecha_examen', 'cancelled'];
}
