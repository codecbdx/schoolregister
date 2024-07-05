<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    use HasFactory;

    protected $fillable = ['user_type_id', 'route_name', 'cancelled'];

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
                    $q->where('route_name', 'LIKE', '%' . $query . '%');
                });
    }

    public static function routes()
    {
        $routes = [
            ['name' => 'Inicio', 'route' => 'home'],
            ['name' => 'Editar perfil', 'route' => 'edit_profile'],
            ['name' => 'Usuarios', 'route' => 'users'],
            ['name' => 'Crear usuario', 'route' => 'create_user'],
            ['name' => 'Editar usuario', 'route' => 'edit_user'],
            ['name' => 'Eliminar usuario', 'route' => 'delete_user'],
            ['name' => 'Alumnos', 'route' => 'students'],
            ['name' => 'Crear alumno', 'route' => 'create_student'],
            ['name' => 'Editar alumno', 'route' => 'edit_student'],
            ['name' => 'Editar perfil de usuario del alumno', 'route' => 'edit_profile_student'],
            ['name' => 'Editar pagos del alumno', 'route' => 'edit_payment_student'],
            ['name' => 'Historial de pagos del alumno', 'route' => 'history_payment_student'],
            ['name' => 'Eliminar alumno', 'route' => 'delete_student'],
            ['name' => 'Grupos', 'route' => 'groups'],
            ['name' => 'Crear grupo', 'route' => 'create_group'],
            ['name' => 'Editar grupo', 'route' => 'edit_group'],
            ['name' => 'Eliminar grupo', 'route' => 'delete_group'],
            ['name' => 'Cursos', 'route' => 'courses'],
            ['name' => 'Crear curso', 'route' => 'create_course'],
            ['name' => 'Editar curso', 'route' => 'edit_course'],
            ['name' => 'Eliminar curso', 'route' => 'delete_course'],
            ['name' => 'Códigos postales', 'route' => 'zip_codes'],
            ['name' => 'Permisos', 'route' => 'permissions'],
            ['name' => 'Crear permiso', 'route' => 'create_permission'],
            ['name' => 'Editar permiso', 'route' => 'edit_permission'],
            ['name' => 'Eliminar permiso', 'route' => 'delete_permission'],
            ['name' => 'Centros educativos', 'route' => 'customers'],
            ['name' => 'Crear centro educativo', 'route' => 'create_customer'],
            ['name' => 'Editar centro educativo', 'route' => 'edit_customer'],
            ['name' => 'Eliminar centro educativo', 'route' => 'delete_customer'],
            ['name' => 'Configuración general', 'route' => 'general_configuration'],
            ['name' => 'Perfiles de Egreso', 'route' => 'areas'],
            ['name' => 'Crear perfil de egreso', 'route' => 'create_area'],
            ['name' => 'Editar perfil de egreso', 'route' => 'edit_area'],
            ['name' => 'Eliminar perfil de egreso', 'route' => 'delete_area'],
            ['name' => 'Carreras', 'route' => 'careers'],
            ['name' => 'Crear carrera', 'route' => 'create_career'],
            ['name' => 'Editar carrera', 'route' => 'edit_career'],
            ['name' => 'Eliminar carrera', 'route' => 'delete_career'],
            ['name' => 'Medios de interacción', 'route' => 'means_interaction'],
            ['name' => 'Crear medio de interacción', 'route' => 'create_mean_interaction'],
            ['name' => 'Editar medio de interacción', 'route' => 'edit_mean_interaction'],
            ['name' => 'Eliminar medio de interacción', 'route' => 'delete_mean_interaction'],
            ['name' => 'Universidades', 'route' => 'universities'],
            ['name' => 'Crear universidad', 'route' => 'create_university'],
            ['name' => 'Editar universidad', 'route' => 'edit_university'],
            ['name' => 'Eliminar universidad', 'route' => 'delete_university'],
            ['name' => 'Educación media superior', 'route' => 'high_school'],
            ['name' => 'Crear educación media superior', 'route' => 'create_high_school'],
            ['name' => 'Editar educación media superior', 'route' => 'edit_high_school'],
            ['name' => 'Eliminar educación media superior', 'route' => 'delete_high_school'],
            ['name' => 'Conceptos de pago', 'route' => 'concepts'],
            ['name' => 'Crear concepto de pago', 'route' => 'create_concept'],
            ['name' => 'Editar concepto de pago', 'route' => 'edit_concept'],
            ['name' => 'Eliminar concepto de pago', 'route' => 'delete_concept'],
            ['name' => 'Tipos de pago', 'route' => 'payment_types'],
            ['name' => 'Crear tipo de pago', 'route' => 'create_payment_type'],
            ['name' => 'Editar tipo de pago', 'route' => 'edit_payment_type'],
            ['name' => 'Eliminar tipo de pago', 'route' => 'delete_payment_type']
        ];

        return $routes;
    }
}
