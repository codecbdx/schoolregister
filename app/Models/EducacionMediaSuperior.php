<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducacionMediaSuperior extends Model
{
    use HasFactory;

    protected $table = 'educacion_media_superior';

    protected $fillable = ['curp', 'escuela', 'area_terminal', 'estatus', 'grado', 'promedio_final_aproximado', 'cancelled'];
}
