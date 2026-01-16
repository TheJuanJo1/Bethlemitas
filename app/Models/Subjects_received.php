<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects_received extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_group',
        'id_area',
    ];

}
