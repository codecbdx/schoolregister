<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Grupos extends Model
{
    use HasFactory;

    protected $fillable = [
        'grupo',
        'inicio_periodo',
        'fin_periodo',
        'fecha_corte',
        'ciclo',
        'modalidad',
        'precio_mensualidad',
        'precio_total',
        'inscripcion',
        'cantidad_max_alumnos',
        'curso_id',
        'customer_id',
        'cancelled',
    ];

    public static function totalCount($customer_id)
    {
        return static::query()
            ->where('customer_id', $customer_id)
            ->where('cancelled', '!=', 1)
            ->count();
    }

    public static function search($query, $customer_id)
    {
        return empty($query) ? static::query()
            ->where('customer_id', $customer_id)
            ->where('cancelled', '!=', 1)
            : static::query()
                ->where('customer_id', $customer_id)
                ->where('cancelled', '!=', 1)
                ->where(function ($q) use ($query) {
                    $q->where('grupo', 'LIKE', '%' . $query . '%')
                        ->orWhere('ciclo', 'LIKE', '%' . $query . '%')
                        ->orWhere('modalidad', 'LIKE', '%' . $query . '%');
                });
    }
}
