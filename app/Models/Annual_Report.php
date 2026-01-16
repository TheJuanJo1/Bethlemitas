<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annual_Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'competencies',
        'aspect',
        'observation',
        'performance_observation',
        'recommendation',
        'id_user_student',
        'id_user_teacher',
        'id_degree',
    ];

    public function students() {
        return $this->belongsTo(Users_student::class,'id_user_student');
    }

    public function degrees() {

        return $this->belongsTo(Degree::class, 'id_degree');
    }

    public function teachers() {

        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }

}
