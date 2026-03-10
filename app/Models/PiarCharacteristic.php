<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiarCharacteristic extends Model
{

    protected $fillable = [

        'piar_id',
        'descripcion_estudiante',
        'gustos_intereses',
        'expectativas_familia',
        'habilidades',
        'apoyos_requeridos'

    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
    }
}