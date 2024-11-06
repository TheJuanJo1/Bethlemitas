<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_asignature',
    ];

    // Relación con teachers_asignatures_groups
    public function teachersGroups()
    {
        return $this->hasMany(Teachers_asignatures_group::class, 'id_asignature');
    }

    // Relación con users_teachers
    public function teachers()
    {
        return $this->belongsToMany(Users_teacher::class, 'teachers_asignatures_groups', 'id_asignature', 'id_teacher');
    }
}
