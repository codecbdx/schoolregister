<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionGeneral extends Model
{
    use HasFactory;

    protected $table = 'configuracion_general';

    protected $fillable = [
        'system_name',
        'system_logo',
        'system_icon',
        'background_login',
        'form_image',
    ];
}
