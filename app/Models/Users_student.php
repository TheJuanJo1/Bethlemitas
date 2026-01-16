<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Function_;
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
        'birthplace',
        'address',
        'type_documment',
        'phone',
        'email',
        'victim_conflict',
        'id_carer',
        'id_degree',
        'id_group',
        'id_institution',
        'sent_by',
        'id_state',
        'activation_period',
    ];



    // Relación con el modelo Users_teacher
    public function teacher()
    {
        return $this->belongsTo(Users_teacher::class, 'sent_by');
    }

    // Relación con el modelo Degree
    public function degree()
    {
        return $this->belongsTo(Degree::class, 'id_degree');
    }

    // Relacion con el modelo Cares
    public function care()
    {
        return $this->belongsTo(Cares::class, 'id_carer');
    }

    // Relación con el modelo Institution
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'id_institution');
    }

    // Relacion con el modelo Grupo Etnico
    public function ethnicGroup()
    {
        return $this->belongsTo(Ethnic_group::class, 'id_ethnic_group');
    }

    // Relacion con el modelo Therapies
    public function therapies()
    {
        return $this->belongsTo(Therapies::class, 'id_therapie');
    }

    // Obtiene el estudiante al que pertenece este registro
    public function health()
    {
        return $this->hasMany(Health::class, 'id_health');
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

    // Relacion con el modelo Deparment 
    public function deparments()
    {
        return $this->belongsTo(Deparment::class, 'id_deparment');
    }

    // Relación con el modelo State
    public function states()
    {
        return $this->belongsTo(State::class, 'id_state'); // 'id_state' es la llave foránea en users_teachers
    }

    // Obtener el registro más reciente de referrals asociado a cada estudiante.
    public function latestReferral()
    {
        return $this->hasOne(Referral::class, 'id_user_student')->latestOfMany();
    }
}
