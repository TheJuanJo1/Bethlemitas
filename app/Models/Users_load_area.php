<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_load_area extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_teacher',
        'id_signature',
    ];
}
