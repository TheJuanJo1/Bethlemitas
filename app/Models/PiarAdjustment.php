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
        'evaluacion',

        'ajuste_curricular',
        'ajuste_metodologico',
        'ajuste_evaluativo',
        'convivencia',
        'socializacion',
        'participacion',
        'autonomia',
        'autocontrol',
        'evaluacion',
        'start_date',
        'teacher_signature',
    ];

    public function piar()
    {
        return $this->belongsTo(Piar::class);
    }
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Users_teacher::class,'teacher_id');
    }

    /* =====================================================
    | HISTORIAL DE PERIODOS (PIAR)
    ===================================================== */
    public function show_piar_periods_history(string $id)
    {
        // Buscamos al estudiante con su PIAR y sus ajustes
        $student = Users_student::with(['piar.adjustments'])->findOrFail($id);
        
        if (!$student->piar) {
            return back()->with('error', 'Este estudiante aún no tiene un proceso PIAR iniciado.');
        }

        // Obtenemos los ajustes y los agrupamos por el AÑO de la fecha de creación
        $history = $student->piar->adjustments()
            ->orderBy('created_at', 'desc')
            ->orderBy('period', 'asc') // Usamos 'period' como está en tu modelo
            ->get()
            ->groupBy(function($item) {
                // Esto extrae el año (ej: 2026) de la fecha en que se creó el registro
                return $item->created_at->format('Y'); 
            });

        return view('psycho.piarPeriodsHistory', compact('student', 'history'));
    }
     
}