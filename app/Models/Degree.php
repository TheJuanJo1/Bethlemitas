<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users_load_degree;
use App\Models\Group;

class Degree extends Model
{
    use HasFactory;

    protected $table = 'degrees';

    protected $fillable = [
        'degree',
    ];

    /* =========================
     * RELACIONES
     * ========================= */

    /**
     * 🔹 Usuarios (psicoorientadores) asignados a este grado
     */
    public function users_load_degrees()
    {
        return $this->hasMany(
            Users_load_degree::class,
            'id_degree'
        );
    }

    /**
     * 🔹 Grupos pertenecientes al grado
     * Relación MANY TO MANY (tabla pivote)
     */
    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'degree_groups', // tabla pivote
            'id_degree',     // FK en degree_groups
            'id_group'       // FK en degree_groups
        );
    }
}