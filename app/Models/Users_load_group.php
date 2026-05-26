<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_load_group extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user_teacher',
        'id_group',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }
}
