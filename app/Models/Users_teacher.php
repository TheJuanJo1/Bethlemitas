<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; // 👈 IMPORTANTE
use Spatie\Permission\Traits\HasRoles;

class Users_teacher extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable; // 👈 AGREGADO

    protected $table = 'users_teachers';

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'document',
        'phone',
        'id_state',
        'group_director',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ======================================================
     |  RELACIONES BÁSICAS
     ====================================================== */

    // Estado del usuario (1 = activo, 2 = PIAR)
    public function state()
    {
        return $this->belongsTo(State::class, 'id_state');
    }

    // Grupo del que es director
    public function director()
    {
        return $this->belongsTo(Group::class, 'group_director');
    }

    /* ======================================================
     |  RELACIONES ACADÉMICAS
     ====================================================== */

    // Áreas asignadas al docente
    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_area'
        )->withPivot('id_group');
    }

    // Grupos asignados al docente
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'teachers_areas_groups',
            'id_teacher',
            'id_group'
        )->withPivot('id_area');
    }

    // Relación completa a la tabla pivote
    public function areasGroups()
    {
        return $this->hasMany(Teachers_areas_groups::class, 'id_teacher');
    }

    /* ======================================================
     |  PSICOORIENTADOR / PIAR
     ====================================================== */

    // Relación directa a la tabla intermedia
    public function loadDegrees()
    {
        return $this->hasMany(Users_load_degree::class, 'id_user');
    }

    // Relación limpia a grados
    public function degrees()
    {
        return $this->belongsToMany(
            Degree::class,
            'users_load_degrees',
            'id_user',
            'id_degree'
        );
    }

    /* ======================================================
     |  SCOPES (FILTROS REUTILIZABLES)
     ====================================================== */

    // Usuarios activos
    public function scopeActivos($query)
    {
        return $query->where('id_state', 1);
    }

    // Usuarios PIAR
    public function scopePiar($query)
    {
        return $query->where('id_state', 2);
    }

    // Solo docentes, psicoorientadores o coordinador
    public function scopeDocentesPsicoCoordinador($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->whereIn('name', [
                'docente',
                'psicoorientador',
                'coordinador'
            ]);
        });
    }
}