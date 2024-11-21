<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_student',
        'id_user_teacher',
        'reason',
        'observation',
        'strategies',
        'course',
    ];

    //Relacion con la tabla de estudiantes
    public function user_student() {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }

    //Relacion con la tabla del docente
    public function user_teacher() {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }
}
