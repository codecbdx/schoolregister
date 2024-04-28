<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosAlumno extends Model
{
    use HasFactory;

    protected $table = 'documentacion';

    protected $fillable = ['curp', 'tipo_documento', 'archivo_pdf', 'cancelled'];
}
