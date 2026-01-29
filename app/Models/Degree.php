<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * 🔹 Usuarios asignados a este grado
     * (psicoorientadores)
     */
    public function users_load_degrees()
    {
        return $this->hasMany(
            Users_load_degree::class,
            'id_degree'
        );
    }

    /**
     * 🔹 Grupos que pertenecen a este grado
     * (ESTO ES CLAVE para mostrar grupos de psicoorientadores)
     */
    public function groups()
    {
        return $this->hasMany(
            Group::class,
            'id_degree'
        );
    }
}
