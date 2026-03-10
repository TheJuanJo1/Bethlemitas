<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta_piar extends Model
{
    use HasFactory;

    protected $table = 'acta_piars';

    protected $fillable = [
        'id_student_characteristics',
        'id_user_teacher',
        'id_area',
        'id_institution',
        'id_period',
        'overview',
        'student_personal_description',
        'areas_learning',
        'objetives_purposes',
        'barriers',
        'reasonable_adjustments',
        'adjustment_evaluation',
        'production_date',
    ];

    // Estudiante
    public function studentCharacteristics()
    {
        return $this->belongsTo(Student_characteristic::class, 'id_student_characteristics');
    }

    // Docente
    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }

    // Área
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    // Periodo
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period');
    }

    // Institución
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }
}