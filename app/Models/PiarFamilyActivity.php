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
        'activity',
        'strategy',
        'frequency',
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
    }
}
