<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers_asignatures_group extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_teacher',
        'id_asignature',
        'id_group',
    ];

    // Relación con UserTeacher
    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_teacher');
    }

    // Relación con Asignature
    public function asignature()
    {
        return $this->belongsTo(Asignature::class, 'id_asignature');
    }

    // Relación con Group
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }
}
