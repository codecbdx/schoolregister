<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'responsable',
        'correo',
        'celular',
        'banco',
        'titular',
        'clabe',
        'numero_cuenta',
    ];

    public static function totalCount()
    {
        return static::query()
            ->where('cancelled', 0)
            ->count();
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            ->where('cancelled', 0)
            : static::query()
                ->where('cancelled', 0)
                ->where(function ($q) use ($query) {
                    $q->where('nombre', 'LIKE', '%' . $query . '%')
                        ->orWhere('correo', 'LIKE', '%' . $query . '%')
                        ->orWhere('celular', 'LIKE', '%' . $query . '%');
                });
    }
}
