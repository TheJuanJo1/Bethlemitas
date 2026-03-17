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
        'teacher_id',
        'evaluacion'
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
    }
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Users_teacher::class,'teacher_id');
    }
     
}