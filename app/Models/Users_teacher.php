<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users_teacher extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $guard_name = 'web';

    protected $fillable = [
        'number_documment',
        'name',
        'last_name',
        'group_director',
        'id_state',
        'email',
        'signature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_load_groups', 'id_user_teacher', 'id_group');
    }

    public function asignatures()
    {
        return $this->belongsToMany(Asignature::class, 'users_load_asignatures', 'id_user_teacher', 'id_asignature');
    }

    public function load_degrees()
    {
        return $this->belongsToMany(Degree::class, 'users_load_degrees', 'id_user', 'id_degree');
    }

    public function director() {
        return $this->belongsTo(Group::class, 'group_director');
    }

    public function states() {
        return $this->belongsTo(State::class, 'id_state'); // 'id_state' es la llave foránea en users_teachers
    }

    // Relación con  teachers_asignatures_groups
    public function asignaturesGroups()
    {
        return $this->hasMany(Teachers_asignatures_group::class, 'id_teacher');
    }

    // Relación con asignatures (Con la tabla de teachers_signatures_groups)
    public function asignatures_g()
    {
        return $this->belongsToMany(Asignature::class, 'teachers_asignatures_groups', 'id_teacher', 'id_asignature');
    }

    // Relación con groups (Con la tabla de teachers_signatures_groups)
    public function groups_a()
    {
        return $this->belongsToMany(Group::class, 'teachers_asignatures_groups', 'id_teacher', 'id_group');
    }

    // *** Relación para consultar los grupos relacionados con cada asignatura que imparte el docente en determinados grupos. ***/
    // Paso como parametro el id de la asignatura con la que va a consultar la relación.
    public function groupsForAsignature($asignatureId) {
        // Relacion con la tabla teachers_asignatures_groups
        return $this->belongsToMany(Group::class, 'teachers_asignatures_groups', 'id_teacher', 'id_group')->where('id_asignature', $asignatureId); // Filtra por asignatura
    } 

}
