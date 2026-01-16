<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
    ];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'subjects_receiveds', 'id_group', 'id_area');
    }

    // Relación con teachers_areas_groups
    public function teachersGroups()
    {
        return $this->hasMany(Teachers_areas_group::class, 'id_group');
    }

    // Relacion con los user_groups
    public function load_user_groups()
    {
        return $this->hasMany(Users_load_group::class, 'id_group');
    }

    // Relación con users_teachers
    public function teachers()
    {
        return $this->belongsToMany(Users_teacher::class, 'users_load_groups', 'id_group', 'id_user_teacher');
    }
} 
