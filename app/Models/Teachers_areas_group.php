<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers_areas_group extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_teacher',
        'id_area',
        'id_group',
    ];

    // Relación con UserTeacher
    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_teacher');
    }

    // Relación con Area
    public function areas()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    // Relación con Group
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }
}
