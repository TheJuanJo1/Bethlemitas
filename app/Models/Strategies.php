<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strategies extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'estrategies_description',
    ];
}
