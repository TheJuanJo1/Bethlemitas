<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dua extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_description',
        'observation',
        'goal',
        'resource',
        'expected_results'
    ];


    //Relacion con la tabla estudiantes
    public function user_student()
    {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }


    //Relacion con la tabla profesores
    public function user_teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }


    //Relacion con la tabla asignaturas
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }


    //Relacion con la tabla de Periodos
    public function period()
    {
        return $this->belongsTo(Period::class, 'id_period');

    }
}
