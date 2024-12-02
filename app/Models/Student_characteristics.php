<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_characteristics extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_student',
        'id_psico',
        'overview',
        'student_personal_description',
    ];
}
