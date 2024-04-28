<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPagos extends Model
{
    protected $table = 'historial_pagos';

    protected $fillable = ['folio', 'curp', 'abono', 'metodo_pago', 'nota_referencia', 'estado_pago', 'usuario_responsable', 'tipo_documento', 'archivo_pdf', 'cancelled'];

    use HasFactory;
}
