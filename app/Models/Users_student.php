<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Users_student extends Model
{
    use HasFactory, HasRoles;

    protected $guard_name = 'web'; 

    protected $fillable = [
        'number_documment',
        'name',
        'last_name',
        'age',
        'id_degree',
        'id_group',
        'sent_by',
        'id_state',
        'activation_period',
    ];

    // Relación con el modelo Degree
    public function degree()
    {
        return $this->belongsTo(Degree::class, 'id_degree');
    }

    // Relación con el modelo Group
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    // Relación con el modelo Referral
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'id_user_student');
    }
}
