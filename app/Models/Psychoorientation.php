<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psychoorientation extends Model
{
    use HasFactory;

    /**
     * Campos permitidos para asignación masiva
     */
    protected $fillable = [
        'psychologist_writes',
        'id_user_student',
        'report_year', // 🔹 año del informe
        'age_student',
        'group_student',
        'director_group_student',
        'title_report',
        'reason_inquiry',
        'recomendations',
        'annex_one',
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'report_year' => null,
    ];

    /**
     * Relación con la psicoorientadora que escribe el informe
     */
    public function user_psychology()
    {
        return $this->belongsTo(Users_teacher::class, 'psychologist_writes');
    }

    /**
     * Relación con el estudiante
     */
    public function user_student()
    {
        return $this->belongsTo(Users_student::class, 'id_user_student');
    }

    /**
     * Scope para obtener informes de un año específico
     */
    public function scopeYear($query, $year)
    {
        return $query->where('report_year', $year);
    }

    /**
     * Scope para obtener el informe del año actual
     */
    public function scopeCurrentYear($query)
    {
        return $query->where('report_year', now()->year);
    }
}