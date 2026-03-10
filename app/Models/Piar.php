<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piar extends Model
{
    protected $table = 'piar'; // 👈 IMPORTANTE

    protected $fillable = [
        'student_id',
        'teacher_id',
        'year',
        'institution',
        'sede',
        'jornada'
    ];

    public function student()
    {
        return $this->belongsTo(Users_student::class,'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class,'teacher_id');
    }

    public function characteristics()
    {
        return $this->hasOne(PiarCharacteristic::class);
    }

    public function adjustments()
    {
        return $this->hasMany(PiarAdjustment::class);
    }
}