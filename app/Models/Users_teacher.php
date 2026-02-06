<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Models\Group;
use App\Models\Area;
use App\Models\Degree;
use App\Models\State;
use App\Models\Institution;
use App\Models\Users_load_degree;
use App\Models\Teachers_areas_group;

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
        'password',
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
     * RELACIONES PRINCIPALES
     * ========================= */

    // 🔹 Grupos donde dicta clase (DOCENTE)
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'users_load_groups',
            'id_user_teacher',
            'id_group'
        );
    }

    // 🔹 Áreas que dicta (DOCENTE)
    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'users_load_areas',
            'id_user_teacher',
            'id_area'
        );
    }

    // 🔹 Grados asignados (PSICOORIENTADOR)
    public function loadDegrees()
    {
        return $this->hasMany(Users_load_degree::class, 'id_user');
    }

    // 🔹 Grupo del que es director
    public function director()
    {
        return $this->belongsTo(Group::class, 'group_director');
    }

    // 🔹 Estado
    public function states()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    // 🔹 Institución
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    /* =========================
     * RELACIONES ACADÉMICAS
     * ========================= */

    public function areasGroups()
    {
        return $this->hasMany(Teachers_areas_group::class, 'id_teacher');
    }

    public function areas_g()
    {
        return $this->belongsToMany(
            Area::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_area'
        );
    }

    public function groups_a()
    {
        return $this->belongsToMany(
            Group::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_group'
        );
    }

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
