<?php

namespace App\Http\Controllers;

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

        $periodo1 = PiarAdjustment::where('piar_id',$id)->where('period',1)->get();
        $periodo2 = PiarAdjustment::where('piar_id',$id)->where('period',2)->get();
        $periodo3 = PiarAdjustment::where('piar_id',$id)->where('period',3)->get();

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

        // Si ya existe PIAR ir directo a periodos
        if($piar){
            return redirect()->route('piar.periodos',$piar->id);
        }

        // Si no existe mostrar formulario inicial
        return view('piar.create',compact('student'));

    }


    public function store(Request $request)
    {

        // Verificar si ya existe PIAR este año
        $piar = Piar::where('student_id',$request->student_id)
                ->where('year',date('Y'))
                ->first();

        if(!$piar){

            $piar = Piar::create([
                'student_id' => $request->student_id,
                'teacher_id' => Auth::id(),
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

        return redirect()->route('piar.periodos', $piar->id);

    }

    //Los Periosdos

    public function periodos($piar_id)
    {

        $piar = Piar::findOrFail($piar_id);

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
            'period3'
        ));

    }

    //Periodo Uno

    public function periodo1($piar_id)
    {
        $piar = Piar::with('student')->findOrFail($piar_id);

        return view('piar.periodo1', compact('piar'));
    }

    public function storePeriodo1(Request $request)
    {

        foreach ($request->area as $index => $area) {

            if($area != null){

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 1, // 👈 ESTA ES LA CLAVE DEL ERROR

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => $request->evaluacion[$index] ?? null,

                ]);

            }

        }

        return back()->with('success','Periodo 1 guardado correctamente');

    }

    public function pdfPeriodo1($piar_id)
    {

        $piar = Piar::with('student')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::where('piar_id',$piar_id)
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

        return view('piar.periodo2', compact('piar'));
    }

    public function storePeriodo2(Request $request)
    {

        foreach ($request->area as $index => $area) {

            if($area != null){

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 2, // 👈 ESTA ES LA CLAVE DEL ERROR

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => $request->evaluacion[$index] ?? null,

                ]);

            }

        }

        return back()->with('success','Periodo 2 guardado correctamente');

    }

    public function pdfPeriodo2($piar_id)
    {

        $piar = Piar::with('student')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::where('piar_id',$piar_id)
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

        return view('piar.periodo3', compact('piar'));
    }

    public function storePeriodo3(Request $request)
    {

        foreach ($request->area as $index => $area) {

            if($area != null){

                PiarAdjustment::create([

                    'piar_id' => $request->piar_id,
                    'period' => 3, // 👈 ESTA ES LA CLAVE DEL ERROR

                    'area' => $area,
                    'objetivo' => $request->objetivo[$index] ?? null,
                    'barrera' => $request->barrera[$index] ?? null,
                    'ajuste' => $request->ajuste[$index] ?? null,
                    'evaluacion' => $request->evaluacion[$index] ?? null,

                ]);

            }

        }

        return back()->with('success','Periodo 3 guardado correctamente');

    }

    public function pdfPeriodo3($piar_id)
    {

        $piar = Piar::with('student')->findOrFail($piar_id);

        $adjustments = PiarAdjustment::where('piar_id',$piar_id)
                        ->where('period',1)
                        ->get();

        $pdf = PDF::loadView('pdf.piar_periodo3',compact(
            'piar',
            'adjustments'
        ));

        return $pdf->stream('piar_periodo3.pdf');

    }


}