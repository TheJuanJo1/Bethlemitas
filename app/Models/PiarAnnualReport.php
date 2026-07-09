<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiarAnnualReport extends Model
{
    use HasFactory;

    protected $table = 'piar_annual_reports';

    protected $fillable = [
        'piar_id',
        'competencies',
        'aspects',
        'behavior_observation',
        'academic_observation',
        'recommendations',
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class, 'piar_id');
    }

    public function cleanField($field)
    {
        $value = $this->{$field} ?? '';
        // Reemplazar espacios duros no separables (&nbsp;) por espacios normales
        $value = str_replace('&nbsp;', ' ', $value);
        // Decodificar entidades HTML (como &amp;, &quot;, &lt;, &gt;)
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Eliminar etiquetas HTML residuales y espacios adicionales
        return trim(strip_tags($value));
    }
}
