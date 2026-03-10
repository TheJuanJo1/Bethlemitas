<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiarMinute extends Model
{
    protected $fillable = [
        'id_user_student',
        'teacher_id',
        'act_number',
        'act_date',
        'act_file'
    ];

    public function student()
    {
        return $this->belongsTo(Users_student::class,'id_user_student');
    }

    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class,'teacher_id');
    }
}