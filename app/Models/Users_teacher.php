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
        'id_institution',
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

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'users_load_areas', 'id_user_teacher', 'id_area');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
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

    // Relación con  teachers_areas_groups
    public function areasGroups()
    {
        return $this->hasMany(Teachers_areas_group::class, 'id_teacher');
    }

    // Relación con areas (Con la tabla de teachers_areas_groups)
    public function areas_g()
    {
        return $this->belongsToMany(Area::class, 'teachers_areas_groups', 'id_teacher', 'id_area');
    }

    // Relación con groups (Con la tabla de teachers_signatures_groups)
    public function groups_a()
    {
        return $this->belongsToMany(Group::class, 'teachers_areas_groups', 'id_teacher', 'id_group');
    }

    // *** Relación para consultar los grupos relacionados con cada area que imparte el docente en determinados grupos. ***/
    // Paso como parametro el id de la area con la que va a consultar la relación.
    public function groupsForArea($areaId) {
        // Relacion con la tabla teachers_areas_groups
        return $this->belongsToMany(Group::class, 'teachers_areas_groups', 'id_teacher', 'id_group')->where('id_area', $areaId); // Filtra por asignatura
    } 

}
