<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

     protected $fillable = [
        'institution',
    ];  

    public function teachersInstitution()
    {
        return $this->hasMany(Users_teacher::class, 'id_institution');
    }
}
