<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_load_degree extends Model
{
    use HasFactory;

    protected $table = 'users_load_degrees';

    protected $fillable = [
        'id_user',
        'id_degree',
    ];

    /**
     * Relación con el usuario (docente)
     */
    public function user()
    {
        return $this->belongsTo(Users_teacher::class, 'id_user');
    }

    /**
     * ✅ RELACIÓN CON EL GRADO
     * Esta es la que estaba faltando y causaba el error
     */
    public function degree()
    {
        return $this->belongsTo(Degree::class, 'id_degree');
    }
}
