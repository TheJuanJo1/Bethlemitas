<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiarFamilyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'piar_id',
        'period',
        'teacher_id',
        'activity',
        'strategy',
        'frequency',
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
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
