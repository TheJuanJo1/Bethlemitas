<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta_no_piar extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_student',
        'id_user_teacher',
        'id_asignature',
        'id_period',
        'reason',
        'production_date',
    ];

    //Relacion con la tabla estudiantes
    public function user_student() {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }

    //Relacion con la tabla de docentes
    public function user_teacher() {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }

    //Relacion con la tabla de asignaturas
    public function asignature() {
        return $this->belongsTo(Asignature::class, 'id_asignature');
    }

    //Relacion con la tabla de Periodos
    public function period() {
        return $this->belongsTo(Period::class, 'id_period');
    }

}
