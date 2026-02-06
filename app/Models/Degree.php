<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users_load_degree;

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
     * 🔹 Usuarios (psicoorientadores / docentes)
     * asignados a este grado
     */
    public function users_load_degrees()
    {
        return $this->hasMany(
            Users_load_degree::class,
            'id_degree'
        );
    }
}