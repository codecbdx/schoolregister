<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'paternal_lastname',
        'maternal_lastname',
        'email',
        'password',
        'customer_id',
        'user_type_id',
        'user_image',
        'cancelled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function totalCount()
    {
        return static::query()
            ->whereIn('user_type_id', [1, 2, 3])
            ->where('cancelled', '<>', 1)
            ->count();
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            ->whereIn('user_type_id', [1, 2, 3])
            ->where('cancelled', '<>', 1)
            : static::query()
                ->whereIn('user_type_id', [1, 2, 3])
                ->where('cancelled', '<>', 1)
                ->where(function ($q) use ($query) {
                    $q->where('id', 'LIKE', '%' . $query . '%')
                        ->orWhere('email', 'LIKE', '%' . $query . '%')
                        ->orWhere(DB::raw("CONCAT(name, ' ', paternal_lastname, ' ', maternal_lastname)"), 'LIKE', '%' . $query . '%');
                });
    }
}
