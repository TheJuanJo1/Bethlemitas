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
}
