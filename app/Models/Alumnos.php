<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Alumnos extends Model
{
    use HasFactory;

    protected $fillable = [
        'curp',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'telefono_alumno',
        'telefono_emergencia',
        'correo',
        'facebook',
        'instagram',
        'nombre_tutor',
        'apellido_paterno_tutor',
        'apellido_materno_tutor',
        'parentesco_tutor',
        'telefono_tutor',
        'medio_interaccion',
        'usuario_moodle',
        'contrasena_moodle',
        'status',
        'customer_id',
        'cancelled',
    ];

    public static function totalCount($customer_id)
    {
        return static::query()
            ->where('customer_id', $customer_id)
            ->where('cancelled', 0)
            ->count();
    }

    public static function search($query, $customer_id)
    {
        return empty($query) ? static::query()
            ->where('customer_id', $customer_id)
            ->where('cancelled', 0)
            : static::query()
                ->where('customer_id', $customer_id)
                ->where('cancelled', 0)
                ->where(function ($q) use ($query) {
                    $q->where('curp', 'LIKE', '%' . $query . '%')
                        ->orWhere(DB::raw("CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'LIKE', '%' . $query . '%')
                        ->orWhere('correo', 'LIKE', '%' . $query . '%')
                        ->orWhere('telefono_alumno', 'LIKE', '%' . $query . '%')
                        ->orWhere('telefono_emergencia', 'LIKE', '%' . $query . '%')
                        ->orWhere('telefono_tutor', 'LIKE', '%' . $query . '%');
                });
    }
}
