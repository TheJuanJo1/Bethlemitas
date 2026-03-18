<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_area',
    ];

    // Relación con teachers_areas_groups
    public function teachersGroups()
    {
        return $this->hasMany(Teachers_areas_group::class, 'id_area');
    }

    // Relación con users_teachers
    public function teachers()
    {
        return $this->belongsToMany(
            Users_teacher::class,
            'users_load_areas',
            'id_area',
            'id_user_teacher'
        );
    }
}
