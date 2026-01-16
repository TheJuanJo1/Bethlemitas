<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_parenting',
        'under_protection',
        'id_user_student',
        'pum_brothers',
        'place_occupied',
        'live_with',
        'receive_suppport',
        'organization_name',
        'mother_name',
        'father_name',
        'mother_occupation',
        'father_occupation',
        'mother_education_level',
        'father_education_level',

    ];

    public function user_student()
    {
        return $this->belongsTo(Users_student::class,  'id_student');
    }

}
