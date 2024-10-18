<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users_teacher extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $guard_name = 'web';

    protected $fillable = [
        'number_documment',
        'name',
        'last_name',
        'group_director',
        'id_state',
        'email',
        'signature',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_load_groups', 'id_user_teacher', 'id_group');
    }

    public function asignatures()
    {
        return $this->belongsToMany(Asignature::class, 'users_load_asignatures', 'id_user_teacher', 'id_asignature');
    }

    public function load_degrees()
    {
        return $this->belongsToMany(Degree::class, 'users_load_degrees', 'id_user', 'id_degree');
    }

    public function director() {
        return $this->belongsTo(Group::class, 'group_director');
    }

}
