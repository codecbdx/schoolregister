<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['folio', 'curp', 'fecha_vencimiento', 'concepto', 'cargo', 'abono', 'estado_pago', 'usuario_responsable', 'cancelled', 'customer_id'];

    use HasFactory;
}
