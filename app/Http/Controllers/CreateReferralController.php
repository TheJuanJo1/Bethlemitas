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
            'number_documment' => 'required|digits_between:1,20',
            'name' => 'required',
            'last_name' => 'required',
            'degree' => 'required',
            'group' => 'required',
            'age' => 'nullable|integer|min:0',  // Validación de edad si se ingresa
            'reason_referral' => 'required|string',
            'observation' => 'required|string',
            'strategies' => 'required|string',
        ]);

        $input_number_documment = $request->number_documment;
        $exist_student = Users_student::where('number_documment', $input_number_documment)->first();

        $degreeLoad = Users_load_degree::where('id_degree', $request->input('degree'))->first();

        if (!$degreeLoad) {
            // Manejar el caso donde no se encuentra el grado en Users_load_degree
            return redirect()->back()->with('error', 'No se encontró un psicoorientador asignado para el grado seleccionado, comunicate con cordinación académica para que asigne a un psicoorientador.');
        }

        $id_psico = $degreeLoad->id_user;

        $psico_date = Users_teacher::where('id', $id_psico)->first();

        if ($exist_student) {

            $current_state = $exist_student->id_state;
            // Obtener los datos del estado actual
            $state_info = State::where('id', $current_state)->firstOrFail();
            if (in_array($state_info->state, ['activo', 'en espera'])) {
                return redirect()->back()->with('info', 'El estudiante ya está remitido, pero aún no ha ingresado al proceso PIAR.');
            } elseif ($state_info->state === 'en PIAR') {
                return redirect()->back()->with('info', 'El estudiante ya está en proceso PIAR.');
            } elseif ($state_info->state === 'descartado') {

                DB::beginTransaction();

                try {
                    $exist_student->update([
                        'age' => $request->age, // Actualizar datos de la edad
                        'id_degree' => $request->degree, // Actualizar datos del grado
                        'id_group' => $request->group, // Actualizar datos del grupo
                        'sent_by' => $id_teacher, // Actualizar el docente que lo remite
                        'id_state' => $state->id,
                    ]);

                    // Obtener el nombre del grado usando la relación
                    $degreeName = $exist_student->degree->degree;  // Esto obtiene el nombre del grado relacionado

                    $referral = new Referral();
                    $referral->id_user_student = $exist_student->id; // Se guarda el id del usuario que se crea anteriormente.
                    $referral->id_user_teacher = $id_teacher;
                    $referral->reason = $request->reason_referral;
                    $referral->observation = $request->observation;
                    $referral->strategies = $request->strategies;
                    $referral->course = $degreeName;
                    $referral->save();

                    Mail::to($psico_date->email)->queue(new CreatedReferralMail($exist_student, $referral));

                    DB::commit();

                    return redirect()->back()->with('success', 'Estudiante remitido exitosamente.');
                }catch (\Exception $e) {
                    DB::rollback();
                    // Registra o muestra el error para depuración
                    return redirect()->back()->with('error', 'Hubo problemas en el proceso. Intentelo nuevamente.');
                }
            }
        }
    
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

    // Listar estudiantes remitidos
    public function index_student_remitted(Request $request)
    {
        $id_teacher = Auth::id(); // Se obtiene el id del docente autentificado.

        // Obtener los estudiantes cuyo id_state está en los estados obtenidos
        $query = Users_student::whereHas('states', function ($q) {
            $q->whereIn('state', ['activo', 'en espera']);
        })->where('sent_by', $id_teacher);

        // Filtrar por búsqueda
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('number_documment', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Ordenar y paginar resultados
        $students = $query->orderBy('name', 'asc')
                        ->orderBy('last_name', 'asc')
                        ->paginate(15);

        // Retornar vista
        return view('teacher.studentListRemitted', compact('students'));
    }

    // Vista de editar estudiante
    public function edit_student(string $id) {

        $student = Users_student::find($id);
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();

        return view('teacher.studentEdit', compact('student', 'degrees', 'groups'));
    }

    // Update student 
    public function update_student(Request $request, string $id){
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name' => 'required|string',
            'last_name' => 'required|string',
            'degree' => 'required',
            'group' => 'required',
            'age' => 'nullable|integer|min:0',  // Validación de edad si se ingresa
        ]);

        $student = Users_student::find($id);
        $datos_actuales = $student->toArray();
        $huboCambios = false;

        $nuevos_datos = [
            'number_documment' => $request->number_documment, 
            'name' => $request->name,
            'last_name' => $request->last_name,
            'id_degree' => $request->degree,
            'id_group' => $request->group,
            'age' => $request->age,
        ];

        // Comparar los datos actuales con los nuevos
        foreach ($nuevos_datos as $key => $value) {
            if ($datos_actuales[$key] != $value) {
                $huboCambios = true;
                break; 
            }
        }

        $degreeLoad = Users_load_degree::where('id_degree', $request->input('degree'))->first();

        if (!$degreeLoad) {
            // Manejar el caso donde no se encuentra el grado en Users_load_degree
            return redirect()->back()->with('error', 'No se encontró un psicoorientador asignado para el grado seleccionado, comunicate con cordinación académica para que asigne a un psicoorientador.');
        }

        if ($huboCambios) {

            $student->update($nuevos_datos);

            return redirect()->back()->with('success', 'Usuario editado correctamente.');
        } else {
            return redirect()->back()->with('info', 'No hubo cambios en el usuario.');
        }
    }

    // Añadir acta, aqui se lista los estudiantes en Piar pero se podran selecionar para añadir un acta.
    public function addMinutes(Request $request) {

        // Obtener los estudiantes cuyo id_state está en los estados obtenidos
        $query = Users_student::whereHas('states', function ($q) {
            $q->whereIn('state', ['en PIAR']);
        });

        // Filtrar por búsqueda
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('number_documment', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Ordenar y paginar resultados
        $students = $query->orderBy('name', 'asc')
                        ->orderBy('last_name', 'asc')
                        ->paginate(15);

        return view('teacher.addMinutes', compact('students'));
    }

}