<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CodigosPostales extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'asentamiento',
        'tipo_asentamiento',
        'municipio',
        'estado',
        'zona'
    ];

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::query()
                ->where(function ($q) use ($query) {
                    $q->where('codigo', 'LIKE', '%' . $query . '%')
                        ->orWhere('asentamiento', 'LIKE', '%' . $query . '%')
                        ->orWhere('tipo_asentamiento', 'LIKE', '%' . $query . '%')
                        ->orWhere('municipio', 'LIKE', '%' . $query . '%')
                        ->orWhere('estado', 'LIKE', '%' . $query . '%')
                        ->orWhere('zona', 'LIKE', '%' . $query . '%');
                });
    }
}
