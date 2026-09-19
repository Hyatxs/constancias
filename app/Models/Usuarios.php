<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'rol',
        'matricula',
        'telefono',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_usuario', 'id_usuario');
    }

    public static function roles()
    {
        return [

            'Maestro' => 'Maestro',
            'Estudiante' => 'Estudiante',
            
        ];
    }

        public static function allRoles()
    {
        return [
            'Administrador' => 'Administrador',
            'Coordinador' => 'Coordinador',
            'Director' => 'Director',
            'Maestro' => 'Maestro',
            'Estudiante' => 'Estudiante',
        ];
    }
}
