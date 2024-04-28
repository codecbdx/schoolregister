<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionesAlumno extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = ['curp', 'codigo_postal', 'calle', 'asentamiento', 'tipo_asentamiento', 'municipio', 'estado', 'cancelled'];
}
