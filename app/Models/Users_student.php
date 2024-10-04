<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_student extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_documment',
        'name',
        'last_name',
        'age',
        'id_degree',
        'id_group',
        'sent_by',
        'id_state',
        'activation_period',
    ];
}
