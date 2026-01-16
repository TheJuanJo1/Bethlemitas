<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_affiliated',
        'eps',
        'affiliation_type',
        'emergency_location',
        'receives_medical_care',
        'care_frequency',
        'medical_diagnosis',
        'has_diagnosis',
        'takes_medication',
        'medication_description',
        'uses_assistive_products',
        'assistive_product_description',
        'id_user_student',
    ];
    
     //Relacion con la tabla estudiantes
    public function user_student() {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }
}