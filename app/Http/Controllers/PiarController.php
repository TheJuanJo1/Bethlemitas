<?php

namespace App\Http\Controllers;

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

        $period1 = PiarAdjustment::where('piar_id',$piar_id)
                    ->where('period',1)
                    ->exists();

        $period2 = PiarAdjustment::where('piar_id',$piar_id)
                    ->where('period',2)
                    ->exists();

        $period3 = PiarAdjustment::where('piar_id',$piar_id)
                    ->where('period',3)
                    ->exists();

        return view('piar.periodos',compact(
            'piar',
            'period1',
            'period2',
            'period3',
            'ready'
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
                'runId' => 'pre-fix',
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

            if($area != null){

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 1, // 👈 ESTA ES LA CLAVE DEL ERROR
                    'teacher_id' => auth()->id(),

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => null,

                ]);

            }

        }

        // #region agent log
        @file_put_contents(
            base_path('debug-99f4e2.log'),
            json_encode([
                'sessionId' => '99f4e2',
                'runId' => 'pre-fix',
                'hypothesisId' => 'H1',
                'location' => 'app/Http/Controllers/PiarController.php:storePeriodo1',
                'message' => 'storePeriodo1 redirecting to periodos',
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
            ->with('success','Periodo 1 guardado correctamente');

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

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 2, // 👈 ESTA ES LA CLAVE DEL ERROR
                    'teacher_id' => auth()->id(),

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => null,

                ]);

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

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 3, // 👈 ESTA ES LA CLAVE DEL ERROR
                    'teacher_id' => auth()->id(),

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => null,

                ]);

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


}