<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cares extends Model
{
    use HasFactory;

    protected $fillable = [
        'carer_name',
        'relationship',
        'education_level',
        'phone',
        'email',
    ];
}
