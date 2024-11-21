<?php

namespace App\Http\Controllers;

use App\Mail\CreatedReferralMail;
use App\Models\Degree;
use App\Models\Group;
use App\Models\Referral;
use App\Models\State;
use App\Models\Users_load_degree;
use App\Models\Users_student;
use App\Models\Users_teacher;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class CreateReferralController extends Controller
{
    // Vista Referral, vista de todo el formulario de remisión
    public function create_referral() {

        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();

        return view('teacher.studentReferral', compact('groups', 'degrees'));
    }

    // Store Referral, al realizar una remision tambie se crea un estudiante, en caso tal que el estudiante ya este registrado se verificara si esta descartado o no, si esta decartado solo cambiaran algunos datos como el estado, el id del docente quien lo remite, en caso de que el estudiante no este descartado habrán avisos de error o de info para notificar al usuario.
    public function store_referral(Request $request) {
        $id_teacher = Auth::id(); // Se obtiene el id del docente autentificado.
        $state = State::where('state', 'activo')->firstOrFail(); // Obtener el estado 'activo'
    
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment',
            'name' => 'required',
            'last_name' => 'required',
            'degree' => 'required',
            'group' => 'required',
            'age' => 'nullable|integer|min:0',  // Validación de edad si se ingresa
            'reason_referral' => 'required|string',
            'observation' => 'required|string',
            'strategies' => 'required|string',
        ]);

        $degreeLoad = Users_load_degree::where('id_degree', $request->input('degree'))->first();

        if (!$degreeLoad) {
            // Manejar el caso donde no se encuentra el grado en Users_load_degree
            return redirect()->back()->with('error', 'No se encontró un psicoorientador asignado para el grado seleccionado, comunicate con cordinación académica para que asigne a un psicoorientador.');
        }

        $id_psico = $degreeLoad->id_user;

        $psico_date = Users_teacher::where('id', $id_psico)->first();
    
        DB::beginTransaction();
    
        try {
            $user = new Users_student();
            $user->number_documment = $request->number_documment;
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->age = $request->age;
            $user->id_degree = $request->degree;
            $user->id_group = $request->group;
            $user->sent_by = $id_teacher;
            $user->id_state = $state->id;  // Asignar solo el id del estado
            $user->assignRole('estudiante');
            $user->save();

            // Obtener el nombre del grado usando la relación
            $degreeName = $user->degree->degree;  // Esto obtiene el nombre del grado relacionado

            $referral = new Referral();
            $referral->id_user_student = $user->id; // Se guarda el id del usuario que se crea anteriormente.
            $referral->id_user_teacher = $id_teacher;
            $referral->reason = $request->reason_referral;
            $referral->observation = $request->observation;
            $referral->strategies = $request->strategies;
            $referral->course = $degreeName;
            $referral->save();
            
            Mail::to($psico_date->email)->queue(new CreatedReferralMail($user, $referral));

            DB::commit();

            return redirect()->back()->with('success', 'Estudiante remitido exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Hubo problemas en el proceso, intentelo nuevamente.');
        }
    }
    
}
