<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psychoorientation extends Model
{
    use HasFactory;

    protected $fillable = [
        'psychologist_writes',
        'id_user_student',
        'title_report',
        'reason_inquiry',
        'recomendations',
        'date',
    ];

    //Rerlacion con la tabla de docentes para extraer datos de la psicoorientadora
    public function user_psychology() {
        return $this->belongsTo(Users_teacher::class, 'psychologist_writes');
    }

    //Relacion con la tabla estudiantes
    public function user_student() {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }
    
}
