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
        $piar = Piar::with('student')->findOrFail($id);

        $pdf = Pdf::loadView('piar.pdf', compact('piar'));

        return $pdf->download('PIAR_'.$piar->student->name.'.pdf');
    }

    public function create($student_id)
    {

        $student = Users_student::findOrFail($student_id);

        $piar = Piar::where('student_id',$student_id)
                ->where('year',date('Y'))
                ->first();

        return view('piar.create',compact('piar','student'));
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
                'institution' => $request->institution,
                'sede' => $request->sede,
                'jornada' => $request->jornada
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

        return redirect()->route('piar.create',$request->student_id);

    }

}