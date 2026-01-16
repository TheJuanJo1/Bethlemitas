<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipalities extends Model
{
    use HasFactory;

    protected $fillable = [

        'municipalitie'
    ];


    public function deparment() {
        
        return $this->belongsTo(Deparment::class, 'id_deparment');
    }
}
