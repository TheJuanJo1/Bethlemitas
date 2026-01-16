<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapies extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapy_type',
        'frequency',
        'id_user_student',
    ];
}
