<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grados extends Model
{
    use HasFactory;

    protected $fillable = ['grado', 'cancelled'];

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
                    $q->where('grado', 'LIKE', '%' . $query . '%');
                });
    }
}
