<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = 
    [
      'id_acta_PIAR',
      'involved_actor',
      'strategies',
         
    ];

    // Relación con el modelo Users_teacher
    public function teacher()
    {
        return $this->belongsToMany(Users_teacher::class, 'involved actor');
    }

    // Relacion con el modelo Piar
    public function PIAR() {
        return $this->belongsTo(Acta_piar::class, 'id_acta_PIAR');
    }
}
