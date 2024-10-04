<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_acta_PIAR',
        'student_commiment',
        'attendant_commiment',
        'date',
    ];

    public function PIAR() {
        return $this->belongsTo(Acta_piar::class, 'id_acta_PIAR');
    }
}
