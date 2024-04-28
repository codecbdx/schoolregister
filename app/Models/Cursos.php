<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo_moodle',
        'imagen',
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
                    $q->where('nombre', 'LIKE', '%' . $query . '%')
                        ->orWhere('codigo_moodle', 'LIKE', '%' . $query . '%')
                        ->orWhere('descripcion', 'LIKE', '%' . $query . '%');
                });
    }
}
