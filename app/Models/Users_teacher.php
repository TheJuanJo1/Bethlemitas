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

    /* =========================
     * RELACIONES
     * ========================= */

    // 🔹 Grupos donde dicta clase
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'users_load_groups',
            'id_user_teacher',
            'id_group'
        );
    }

    // 🔹 Áreas que dicta
    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'users_load_areas',
            'id_user_teacher',
            'id_area'
        );
    }

    // 🔹 Institución
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    // 🔹 Grados asignados
    public function load_degrees()
    {
        return $this->belongsToMany(
            Degree::class,
            'users_load_degrees',
            'id_user',
            'id_degree'
        );
    }

    // ✅ RELACIÓN QUE FALTABA (DIRECTOR DE GRUPO)
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_director');
    }

    // 🔹 Estado
    public function states()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    // 🔹 teachers_areas_groups
    public function areasGroups()
    {
        return $this->hasMany(
            Teachers_areas_group::class,
            'id_teacher'
        );
    }

    // 🔹 Áreas (desde teachers_areas_groups)
    public function areas_g()
    {
        return $this->belongsToMany(
            Area::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_area'
        );
    }

    // 🔹 Grupos (desde teachers_areas_groups)
    public function groups_a()
    {
        return $this->belongsToMany(
            Group::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_group'
        );
    }

    // 🔹 Grupos por área específica
    public function groupsForArea($areaId)
    {
        return $this->belongsToMany(
            Group::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_group'
        )->where('id_area', $areaId);
    }
}
