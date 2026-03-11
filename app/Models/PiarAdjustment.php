<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiarAdjustment extends Model
{

    protected $fillable = [
        'piar_id',
        'period',
        'area',
        'objetivo',
        'barrera',
        'ajuste',
        'evaluacion'
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
    }
}