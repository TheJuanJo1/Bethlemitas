<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Function_;

class Acta_piar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_student',
        'id_user_teacher',
        'id_area',
        'id_institution',
        'id_period',
        'overview',
        'student_personal_description',
        'areas_learning', 
        'objetives_purposes',
        'barries',
        'reasonable_adjustments',
        'adjustment_evaluation',
        'production_date',
    ];

    //Relacion con la tabla estudiantes
    public function user_student()
    {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }

    //Relacion con la tabla de docentes
    public function user_teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }

    //Relacion con la tabla de areas
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    //Relacion con la tabla de Periodos
    public function period()
    { 
        return $this->belongsTo(Period::class, 'id_period');

    }

    //Relacion con la tabla de institucion
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');

    }

    public function recommendations()
    {
        // Un PIAR tiene muchas recomendaciones
        return $this->hasMany(Recommendation::class, 'id_acta_PIAR');
    }
}
