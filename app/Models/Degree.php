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

    /**
     * Relación con la tabla users_load_degrees
     * Un grado puede estar asignado a varios docentes
     */
    public function users_load_degrees()
    {
        return $this->hasMany(Users_load_degree::class, 'id_degree');
    }
}
