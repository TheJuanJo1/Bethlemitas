<?php

namespace App\Http\Controllers;

use App\Models\Piar;
use App\Models\PiarCharacteristic;
use App\Models\PiarAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiarController extends Controller
{

    public function create($student_id)
    {

        $piar = Piar::where('student_id',$student_id)
                ->where('year',date('Y'))
                ->first();

        return view('piar.create',compact('piar','student_id'));
    }


    public function store(Request $request)
    {

        $piar = Piar::create([

            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(),
            'year' => date('Y'),
            'institution' => $request->institution,
            'sede' => $request->sede,
            'jornada' => $request->jornada

        ]);

        PiarCharacteristic::create([

            'piar_id'=>$piar->id,
            'descripcion_estudiante'=>$request->descripcion,
            'gustos_intereses'=>$request->gustos,
            'expectativas_familia'=>$request->expectativas,
            'habilidades'=>$request->habilidades,
            'apoyos_requeridos'=>$request->apoyos

        ]);

        return redirect()->route('piar.create',$request->student_id);

    }

}