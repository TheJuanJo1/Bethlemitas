<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
    ];

    public function asignatures()
    {
        return $this->belongsToMany(Asignature::class, 'subjects_receiveds', 'id_group', 'id_asignature');
    }
}
