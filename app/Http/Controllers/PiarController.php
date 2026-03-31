<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Piar;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PiarCharacteristic;
use App\Models\PiarAdjustment;
use App\Models\Users_student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiarController extends Controller
{
   public function pdf($id)
    {
        $piar = Piar::with([
            'student.degree',
            'student.group',
            'teacher',
            'characteristics'
        ])->findOrFail($id);

        if (!$piar->characteristics) {
            return back()->with('error', 'El acta no está disponible hasta que el Psicoorientador diligencie la caracterización.');
        }

        $periodo1 = PiarAdjustment::with('teacher')->where('piar_id',$id)->where('period',1)->get();
        $periodo2 = PiarAdjustment::with('teacher')->where('piar_id',$id)->where('period',2)->get();
        $periodo3 = PiarAdjustment::with('teacher')->where('piar_id',$id)->where('period',3)->get();

        $pdf = Pdf::loadView('pdf.piar_completo', compact(
            'piar',
            'periodo1',
            'periodo2',
            'periodo3'
        ));

        return $pdf->download('PIAR_'.$piar->student->name.'.pdf');
    }

    public function create($student_id)
    {

        $student = Users_student::findOrFail($student_id);

        $piar = Piar::where('student_id',$student_id)
                ->where('year',date('Y'))
                ->first();

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H-create-1',
                'location' => 'app/Http/Controllers/PiarController.php:create',
                'message' => 'PIAR create accessed by psico',
                'data' => [
                    'student_id' => (int) $student_id,
                    'has_piar' => (bool) $piar,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        // Mostrar siempre el formulario inicial; store() decide si crea o solo completa
        return view('piar.create',compact('student'));

    }


    public function store(Request $request)
    {

        $request->validate([
            'student_id' => 'required',
            'descripcion' => 'required|string',
            'gustos' => 'required|string',
            'expectativas' => 'required|string',
            'habilidades' => 'required|string',
            'apoyos' => 'required|string'
        ]);

        $student = Users_student::findOrFail($request->student_id);

        // Verificar si ya existe PIAR este año
        $piar = Piar::where('student_id',$request->student_id)
                ->where('year',date('Y'))
                ->first();

        if(!$piar){

            $piar = Piar::create([
                'student_id' => $request->student_id,
                // El PIAR pertenece al docente del estudiante (no al psico que diligencia la caracterización)
                'teacher_id' => $student->sent_by ?? null,
                'year' => date('Y'),
            ]);

        }

        // Crear caracterización si no existe
        if(!$piar->characteristics){

            PiarCharacteristic::create([
                'piar_id'=>$piar->id,
                'descripcion_estudiante'=>$request->descripcion,
                'gustos_intereses'=>$request->gustos,
                'expectativas_familia'=>$request->expectativas,
                'habilidades'=>$request->habilidades,
                'apoyos_requeridos'=>$request->apoyos
            ]);

        }

        return redirect()
            ->route('psico.students.piar')
            ->with('success', 'PIAR creado/actualizado correctamente');

    }
    //Los Periosdos

    

    public function periodos($piar_id)
    {
        $piar = Piar::with('student.degree', 'characteristics')->findOrFail($piar_id);
        $ready = (bool) $piar->characteristics;

        $period1 = PiarAdjustment::where('piar_id',$piar_id)->where('period',1)->exists();
        $period2 = PiarAdjustment::where('piar_id',$piar_id)->where('period',2)->exists();
        $period3 = PiarAdjustment::where('piar_id',$piar_id)->where('period',3)->exists();

        // 🔥 CONTADOR 30 DÍAS
        $fechaInicio = Carbon::parse($piar->created_at);
        $fechaLimite = $fechaInicio->copy()->addDays(30);
        $hoy = Carbon::now();

        $diasRestantes = $hoy->diffInDays($fechaLimite, false);

        return view('piar.periodos', compact(
            'piar',
            'period1',
            'period2',
            'period3',
            'ready',
            'diasRestantes'
        ));
    }

    //Periodo Uno

    public function periodo1($piar_id)
    {
        $piar = Piar::with('student')->findOrFail($piar_id);

        $teacherId = Auth::id();
        $areas = Area::query()
            ->where(function ($q) use ($teacherId) {
                $q->whereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('users_load_areas')
                        ->where('id_user_teacher', $teacherId);
                })->orWhereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('teachers_areas_groups')
                        ->where('id_teacher', $teacherId);
                });
            })
            ->orderBy('name_area')
            ->get();

        return view('piar.periodo1', compact('piar','areas'));
    }

    public function storePeriodo1(Request $request)
    {
        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'updated',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo1',
                'message' => 'storePeriodo1 called',
                'data' => [
                    'piar_id' => (int) ($request->piar_id ?? 0),
                    'areas_count' => is_array($request->area ?? null) ? count($request->area) : null,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        foreach ($request->area as $index => $area) {

            if ($area != null) {

                $piarId = (int) $request->piar_id;
                $teacherId = (int) auth()->id();

                // #region agent log (duplicados)
                $existingCount = PiarAdjustment::query()
                    ->where('piar_id', $piarId)
                    ->where('period', 1)
                    ->where('teacher_id', $teacherId)
                    ->where('area', $area)
                    ->count();

                if ($existingCount > 0) {
                    @file_put_contents(
                        base_path('debug-99f4e2.log'),
                        json_encode([
                            'sessionId' => '99f4e2',
                            'runId' => 'updated',
                            'hypothesisId' => 'H-dup-create',
                            'location' => 'storePeriodo1:dupCheck',
                            'message' => 'existing adjustment found',
                            'data' => [
                                'piar_id' => $piarId,
                                'period' => 1,
                                'teacher_id' => $teacherId,
                                'area' => $area,
                                'existingCount' => $existingCount,
                                'index' => $index,
                            ],
                            'timestamp' => (int) round(microtime(true) * 1000),
                        ]) . PHP_EOL,
                        FILE_APPEND
                    );
                }
                // #endregion agent log

                // 🔥 NUEVOS CAMPOS
                $values = [
                    'piar_id' => $piarId,
                    'period' => 1,
                    'teacher_id' => $teacherId,

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,

                    'ajuste_curricular' => $request->ajuste_curricular[$index] ?? null,
                    'ajuste_metodologico' => $request->ajuste_metodologico[$index] ?? null,
                    'ajuste_evaluativo' => $request->ajuste_evaluativo[$index] ?? null,

                    'convivencia' => $request->convivencia[$index] ?? null,
                    'socializacion' => $request->socializacion[$index] ?? null,
                    'participacion' => $request->participacion[$index] ?? null,
                    'autonomia' => $request->autonomia[$index] ?? null,
                    'autocontrol' => $request->autocontrol[$index] ?? null,

                    'evaluacion' => null,
                ];

                $baseQuery = PiarAdjustment::query()
                    ->where('piar_id', $piarId)
                    ->where('period', 1)
                    ->where('teacher_id', $teacherId)
                    ->where('area', $area);

                $existingIds = $baseQuery->orderBy('id')->pluck('id');

                if ($existingIds->isNotEmpty()) {

                    // ✅ UPDATE con nuevos campos
                    $baseQuery->update([
                        'objetivo' => $values['objetivo'],
                        'barrera' => $values['barrera'],

                        'ajuste_curricular' => $values['ajuste_curricular'],
                        'ajuste_metodologico' => $values['ajuste_metodologico'],
                        'ajuste_evaluativo' => $values['ajuste_evaluativo'],

                        'convivencia' => $values['convivencia'],
                        'socializacion' => $values['socializacion'],
                        'participacion' => $values['participacion'],
                        'autonomia' => $values['autonomia'],
                        'autocontrol' => $values['autocontrol'],

                        'evaluacion' => null,
                    ]);

                    // 🧹 eliminar duplicados dejando uno
                    $keepId = $existingIds->first();
                    $baseQuery->where('id', '!=', $keepId)->delete();

                } else {
                    PiarAdjustment::create($values);
                }
            }
        }

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'updated',
                'hypothesisId' => 'H1',
                'location' => 'storePeriodo1',
                'message' => 'redirecting',
                'data' => [
                    'piar_id' => (int) ($request->piar_id ?? 0),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        return redirect()
            ->route('piar.periodos', (int) $request->piar_id)
            ->with('success', 'Periodo 1 guardado correctamente');
    }

    public function pdfPeriodo1($piar_id)
    {

        $piar = Piar::with('student','teacher')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::with('teacher')
                ->where('piar_id',$piar_id)
                ->where('period',1)
                ->get();
        $pdf = PDF::loadView('pdf.piar_periodo1',compact(
            'piar',
            'adjustments'
        ));

        return $pdf->stream('piar_periodo1.pdf');

    }

    public function editarPeriodo1($piar_id)
    {
        $piar_id = (int) $piar_id;

        $adjustments = PiarAdjustment::where('piar_id', $piar_id)
            ->where('period', 1)
            ->where('teacher_id', auth()->id()) // 🔥 solo sus propios registros
            ->orderBy('area')
            ->get();

        // 🚨 Validación: que sí existan datos
        if ($adjustments->isEmpty()) {
            return redirect()
                ->route('piar.periodos', $piar_id)
                ->with('error', 'No hay datos para editar en este periodo.');
        }

        return view('piar.editar_periodo1', compact('adjustments', 'piar_id'));
    }

    public function updatePeriodo1(Request $request)
    {
        foreach ($request->adjustment_id as $index => $id) {

            $adj = PiarAdjustment::find($id);

            if ($adj) {
                $adj->update([
                    // ❌ quitar area (NO viene del form)

                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,

                    'ajuste_curricular' => $request->ajuste_curricular[$index] ?? null,
                    'ajuste_metodologico' => $request->ajuste_metodologico[$index] ?? null,
                    'ajuste_evaluativo' => $request->ajuste_evaluativo[$index] ?? null,

                    'convivencia' => $request->convivencia[$index] ?? null,
                    'socializacion' => $request->socializacion[$index] ?? null,
                    'participacion' => $request->participacion[$index] ?? null,
                    'autonomia' => $request->autonomia[$index] ?? null,
                    'autocontrol' => $request->autocontrol[$index] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Periodo 1 actualizado correctamente');
    }

    //Periodo 2
     public function periodo2($piar_id)
    {
        $piar = Piar::with('student')->findOrFail($piar_id);

        $teacherId = Auth::id();
        $areas = Area::query()
            ->where(function ($q) use ($teacherId) {
                $q->whereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('users_load_areas')
                        ->where('id_user_teacher', $teacherId);
                })->orWhereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('teachers_areas_groups')
                        ->where('id_teacher', $teacherId);
                });
            })
            ->orderBy('name_area')
            ->get();

        return view('piar.periodo2', compact('piar','areas'));
    }

    public function storePeriodo2(Request $request)
    {
        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo2',
                'message' => 'storePeriodo2 called',
                'data' => [
                    'piar_id' => (int) ($request->piar_id ?? 0),
                    'areas_count' => is_array($request->area ?? null) ? count($request->area) : null,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        foreach ($request->area as $index => $area) {

            if($area != null){
                // #region agent log
                $existingCount = PiarAdjustment::query()
                    ->where('piar_id', (int) $request->piar_id)
                    ->where('period', 2)
                    ->where('teacher_id', auth()->id())
                    ->where('area', $area)
                    ->count();
                if ($existingCount > 0) {
                    @file_put_contents(
                        base_path('debug-99f4e2.log'),
                        json_encode([
                            'sessionId' => '99f4e2',
                            'runId' => 'pre-fix',
                            'hypothesisId' => 'H-dup-create',
                            'location' => 'storePeriodo2:dupCheck',
                            'message' => 'existing adjustment found; will create duplicate unless fixed',
                            'data' => [
                                'piar_id' => (int) $request->piar_id,
                                'period' => 2,
                                'teacher_id' => (int) auth()->id(),
                                'area' => (string) $area,
                                'existingCount' => (int) $existingCount,
                                'index' => (int) $index,
                            ],
                            'timestamp' => (int) round(microtime(true) * 1000),
                        ]) . PHP_EOL,
                        FILE_APPEND
                    );
                }
                // #endregion agent log

                $piarId = (int) $request->piar_id;
                $teacherId = (int) auth()->id();
                $values = [
                    'piar_id' => $piarId,
                    'period' => 2,
                    'teacher_id' => $teacherId,
                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => null,
                ];

                $baseQuery = PiarAdjustment::query()
                    ->where('piar_id', $piarId)
                    ->where('period', 2)
                    ->where('teacher_id', $teacherId)
                    ->where('area', $area);

                $existingIds = $baseQuery->orderBy('id')->pluck('id');

                if ($existingIds->isNotEmpty()) {
                    $baseQuery->update([
                        'objetivo' => $values['objetivo'],
                        'barrera' => $values['barrera'],
                        'ajuste' => $values['ajuste'],
                        'evaluacion' => null,
                    ]);

                    $keepId = $existingIds->first();
                    $baseQuery->where('id', '!=', $keepId)->delete();
                } else {
                    PiarAdjustment::create($values);
                }

            }

        }

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo2',
                'message' => 'storePeriodo2 redirecting to periodos',
                'data' => [
                    'to' => 'piar.periodos',
                    'piar_id' => (int) ($request->piar_id ?? 0),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        return redirect()
            ->route('piar.periodos', (int) $request->piar_id)
            ->with('success','Periodo 2 guardado correctamente');

    }

    public function pdfPeriodo2($piar_id)
    {

        $piar = Piar::with('student','teacher')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::with('teacher')
                ->where('piar_id',$piar_id)
                ->where('period',2)
                ->get();

        $pdf = PDF::loadView('pdf.piar_periodo2',compact(
            'piar',
            'adjustments'
        ));

        return $pdf->stream('piar_periodo2.pdf');

    }

    public function editarPeriodo2($piar_id)
    {
        $piar_id = (int) $piar_id;

        $adjustments = PiarAdjustment::where('piar_id', $piar_id)
            ->where('period', 2)
            ->where('teacher_id', auth()->id()) // 🔥 solo sus propios registros
            ->orderBy('area')
            ->get();

        // 🚨 Validación: que sí existan datos
        if ($adjustments->isEmpty()) {
            return redirect()
                ->route('piar.periodos', $piar_id)
                ->with('error', 'No hay datos para editar en este periodo.');
        }

        return view('piar.editar_periodo1', compact('adjustments', 'piar_id'));
    }

    public function updatePeriodo2(Request $request)
    {
        foreach ($request->adjustment_id as $index => $id) {

            $adj = PiarAdjustment::find($id);

            if ($adj) {
                $adj->update([
                    // ❌ quitar area (NO viene del form)

                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,

                    'ajuste_curricular' => $request->ajuste_curricular[$index] ?? null,
                    'ajuste_metodologico' => $request->ajuste_metodologico[$index] ?? null,
                    'ajuste_evaluativo' => $request->ajuste_evaluativo[$index] ?? null,

                    'convivencia' => $request->convivencia[$index] ?? null,
                    'socializacion' => $request->socializacion[$index] ?? null,
                    'participacion' => $request->participacion[$index] ?? null,
                    'autonomia' => $request->autonomia[$index] ?? null,
                    'autocontrol' => $request->autocontrol[$index] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Periodo 2 actualizado correctamente');
    }

    //Y PPeiorodo 3
     public function periodo3($piar_id)
    {
        $piar = Piar::with('student')->findOrFail($piar_id);

        $teacherId = Auth::id();
        $areas = Area::query()
            ->where(function ($q) use ($teacherId) {
                $q->whereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('users_load_areas')
                        ->where('id_user_teacher', $teacherId);
                })->orWhereIn('id', function ($sub) use ($teacherId) {
                    $sub->select('id_area')
                        ->from('teachers_areas_groups')
                        ->where('id_teacher', $teacherId);
                });
            })
            ->orderBy('name_area')
            ->get();

        return view('piar.periodo3', compact('piar','areas'));
    }

    public function storePeriodo3(Request $request)
    {
        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo3',
                'message' => 'storePeriodo3 called',
                'data' => [
                    'piar_id' => (int) ($request->piar_id ?? 0),
                    'areas_count' => is_array($request->area ?? null) ? count($request->area) : null,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        foreach ($request->area as $index => $area) {

            if($area != null){
                // #region agent log
                $existingCount = PiarAdjustment::query()
                    ->where('piar_id', (int) $request->piar_id)
                    ->where('period', 3)
                    ->where('teacher_id', auth()->id())
                    ->where('area', $area)
                    ->count();
                if ($existingCount > 0) {
                    @file_put_contents(
                        base_path('debug-99f4e2.log'),
                        json_encode([
                            'sessionId' => '99f4e2',
                            'runId' => 'pre-fix',
                            'hypothesisId' => 'H-dup-create',
                            'location' => 'storePeriodo3:dupCheck',
                            'message' => 'existing adjustment found; will create duplicate unless fixed',
                            'data' => [
                                'piar_id' => (int) $request->piar_id,
                                'period' => 3,
                                'teacher_id' => (int) auth()->id(),
                                'area' => (string) $area,
                                'existingCount' => (int) $existingCount,
                                'index' => (int) $index,
                            ],
                            'timestamp' => (int) round(microtime(true) * 1000),
                        ]) . PHP_EOL,
                        FILE_APPEND
                    );
                }
                // #endregion agent log

                $piarId = (int) $request->piar_id;
                $teacherId = (int) auth()->id();
                $values = [
                    'piar_id' => $piarId,
                    'period' => 3,
                    'teacher_id' => $teacherId,
                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => null,
                ];

                $baseQuery = PiarAdjustment::query()
                    ->where('piar_id', $piarId)
                    ->where('period', 3)
                    ->where('teacher_id', $teacherId)
                    ->where('area', $area);

                $existingIds = $baseQuery->orderBy('id')->pluck('id');

                if ($existingIds->isNotEmpty()) {
                    $baseQuery->update([
                        'objetivo' => $values['objetivo'],
                        'barrera' => $values['barrera'],
                        'ajuste' => $values['ajuste'],
                        'evaluacion' => null,
                    ]);

                    $keepId = $existingIds->first();
                    $baseQuery->where('id', '!=', $keepId)->delete();
                } else {
                    PiarAdjustment::create($values);
                }

            }

        }

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo3',
                'message' => 'storePeriodo3 redirecting to periodos',
                'data' => [
                    'to' => 'piar.periodos',
                    'piar_id' => (int) ($request->piar_id ?? 0),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        return redirect()
            ->route('piar.periodos', (int) $request->piar_id)
            ->with('success','Periodo 3 guardado correctamente');

    }

    public function pdfPeriodo3($piar_id)
    {

        $piar = Piar::with('student','teacher')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::with('teacher')
                ->where('piar_id',$piar_id)
                ->where('period',3)
                ->get();

        $pdf = PDF::loadView('pdf.piar_periodo3',compact(
            'piar',
            'adjustments'
        ));

        return $pdf->stream('piar_periodo3.pdf');

    }

    public function editarPeriodo3($piar_id)
    {
        $piar_id = (int) $piar_id;

        $adjustments = PiarAdjustment::where('piar_id', $piar_id)
            ->where('period', 3)
            ->where('teacher_id', auth()->id()) // 🔥 solo sus propios registros
            ->orderBy('area')
            ->get();

        // 🚨 Validación: que sí existan datos
        if ($adjustments->isEmpty()) {
            return redirect()
                ->route('piar.periodos', $piar_id)
                ->with('error', 'No hay datos para editar en este periodo.');
        }

        return view('piar.editar_periodo1', compact('adjustments', 'piar_id'));
    }
    
    public function updatePeriodo3(Request $request)
    {
        foreach ($request->adjustment_id as $index => $id) {

            $adj = PiarAdjustment::find($id);

            if ($adj) {
                $adj->update([
                    // ❌ quitar area (NO viene del form)

                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,

                    'ajuste_curricular' => $request->ajuste_curricular[$index] ?? null,
                    'ajuste_metodologico' => $request->ajuste_metodologico[$index] ?? null,
                    'ajuste_evaluativo' => $request->ajuste_evaluativo[$index] ?? null,

                    'convivencia' => $request->convivencia[$index] ?? null,
                    'socializacion' => $request->socializacion[$index] ?? null,
                    'participacion' => $request->participacion[$index] ?? null,
                    'autonomia' => $request->autonomia[$index] ?? null,
                    'autocontrol' => $request->autocontrol[$index] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Periodo 3 actualizado correctamente');
    }

    public function evaluacion($piar_id, $period)
    {
        $period = (int) $period;
        abort_unless(in_array($period, [1, 2, 3], true), 404);

        $piar = Piar::with('student')->findOrFail($piar_id);
        $teacherId = Auth::id();

        $adjustments = PiarAdjustment::query()
            ->where('piar_id', $piar_id)
            ->where('period', $period)
            ->where('teacher_id', $teacherId)
            ->orderBy('area')
            ->get();

        return view('piar.evaluacion', compact('piar', 'period', 'adjustments'));
    }

    public function storeEvaluacion(Request $request)
    {
        $data = $request->validate([
            'piar_id' => ['required', 'integer'],
            'period' => ['required', 'integer', 'in:1,2,3'],
            'adjustment_id' => ['required', 'array'],
            'adjustment_id.*' => ['required', 'integer'],
            'evaluacion' => ['required', 'array'],
            'evaluacion.*' => ['nullable', 'string'],
        ]);

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H2',
                'location' => 'app/Http/Controllers/PiarController.php:storeEvaluacion',
                'message' => 'storeEvaluacion called',
                'data' => [
                    'piar_id' => (int) $data['piar_id'],
                    'period' => (int) $data['period'],
                    'rows' => is_array($data['adjustment_id'] ?? null) ? count($data['adjustment_id']) : null,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        $piarId = (int) $data['piar_id'];
        $period = (int) $data['period'];
        $ids = $data['adjustment_id'];
        $evaluaciones = $data['evaluacion'];

        foreach ($ids as $i => $id) {
            PiarAdjustment::query()
                ->where('id', $id)
                ->where('piar_id', $piarId)
                ->where('period', $period)
                ->where('teacher_id', Auth::id())
                ->update([
                    'evaluacion' => $evaluaciones[$i] ?? null,
                ]);
        }

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H2',
                'location' => 'app/Http/Controllers/PiarController.php:storeEvaluacion',
                'message' => 'storeEvaluacion redirecting to periodos',
                'data' => [
                    'to' => 'piar.periodos',
                    'piar_id' => $piarId,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion agent log

        return redirect()
            ->route('piar.periodos', $piarId)
            ->with('success', 'Evaluación guardada correctamente');
    }

    // #region Psico - Edición de Ajustes Razonables

    public function psicoEditarAjustes($piar_id)
    {
        $piar_id = (int) $piar_id;

        $piar = Piar::with([
            'student.degree',
            'characteristics',
        ])->findOrFail($piar_id);

        // Si no está caracterizado, no tiene sentido editar ajustes.
        if (!$piar->characteristics) {
            return redirect()
                ->route('psico.students.piar')
                ->with('error', 'Primero debe existir la caracterización del PIAR (Psico).');
        }

        $periodo1 = PiarAdjustment::query()
            ->where('piar_id', $piar_id)
            ->where('period', 1)
            ->orderBy('area')
            ->get();

        $periodo2 = PiarAdjustment::query()
            ->where('piar_id', $piar_id)
            ->where('period', 2)
            ->orderBy('area')
            ->get();

        $periodo3 = PiarAdjustment::query()
            ->where('piar_id', $piar_id)
            ->where('period', 3)
            ->orderBy('area')
            ->get();

        return view('piar.editar_ajustes', compact('piar', 'periodo1', 'periodo2', 'periodo3'));
    }

    public function psicoGuardarAjustes(Request $request)
    {
        $data = $request->validate([
            'piar_id' => ['required', 'integer'],
            'period' => ['required', 'integer', 'in:1,2,3'],
            'adjustment_id' => ['required', 'array'],
            'adjustment_id.*' => ['required', 'integer'],
            'objetivo' => ['required', 'array'],
            'objetivo.*' => ['nullable', 'string'],
            'barrera' => ['required', 'array'],
            'barrera.*' => ['nullable', 'string'],
            'ajuste' => ['required', 'array'],
            'ajuste.*' => ['nullable', 'string'],
        ]);

        $piarId = (int) $data['piar_id'];
        $period = (int) $data['period'];
        $ids = $data['adjustment_id'];

        foreach ($ids as $i => $adjId) {
            $adjId = (int) $adjId;

            PiarAdjustment::query()
                ->where('id', $adjId)
                ->where('piar_id', $piarId)
                ->where('period', $period)
                ->update([
                    'objetivo' => $data['objetivo'][$i] ?? null,
                    'barrera' => $data['barrera'][$i] ?? null,
                    'ajuste' => $data['ajuste'][$i] ?? null,
                ]);
        }

        return redirect()
            ->route('piar.psico.ajustes.edit', $piarId)
            ->with('success', 'Ajustes actualizados correctamente.');
    }

    // #endregion Psico - Edición de Ajustes Razonables


}