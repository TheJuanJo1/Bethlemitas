<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_load_degree extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_degree',
    ];
}
