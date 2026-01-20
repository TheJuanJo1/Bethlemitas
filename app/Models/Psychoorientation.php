<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psychoorientation extends Model
{
    use HasFactory;

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
        'psychologist_writes',
        'id_user_student',
        'age_student',
        'group_student',
        'director_group_student',
        'title_report',
        'reason_inquiry',
        'recomendations',
        'date',
    ];

    /**
     * Relación con la tabla de docentes
     * Psicoorientadora que escribe el informe
     */
    public function user_psychology()
    {
        return $this->belongsTo(Users_teacher::class, 'psychologist_writes');
    }

    /**
     * Relación con la tabla de estudiantes
     */
    public function user_student()
    {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }
}
