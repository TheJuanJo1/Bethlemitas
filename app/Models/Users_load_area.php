<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_load_area extends Model
{
    use HasFactory;

    protected $table = 'users_load_areas';

    protected $fillable = [
        'id_user_teacher',
        'id_area',
    ];

    // relación con docente
    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'id_user_teacher');
    }

    // relación con área
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }
}