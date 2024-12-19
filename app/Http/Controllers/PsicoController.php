<?php

namespace App\Http\Controllers;

use App\Mail\AcceptStudentMail;
use App\Mail\DiscardStudentMail;
use App\Models\Degree;
use App\Models\Group;
use App\Models\Period;
use App\Models\Psychoorientation;
use App\Models\Referral;
use App\Models\State;
use App\Models\Users_load_degree;
use App\Models\Users_load_group;
use App\Models\Users_student;
use App\Models\Users_teacher;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PsicoController extends Controller
{
    // Remitir estudiantes por parte de la psicooriendora

    // Listar estudiantes remitidos
    public function index_student_remitted_psico(Request $request) {
        $id_psico = Auth::id(); // Obtengo el id de la psicoorientadora autentificada.
        $load_degree = Users_load_degree::where('id_user', $id_psico)->pluck('id_degree')->toArray();

        // Obtener los estudiantes cuyo id_state está en los estados obtenidos
        $query = Users_student::whereHas('states', function ($q) {
            $q->whereIn('state', ['activo']);
        })->whereIn('id_degree', $load_degree);

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

        return view('psycho.listRemitted', compact('students'));
    }

    // Visualizar los dettales de la remision del estudiante
    public function detailsReferral(string $id) {

        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();

        $info_student = Users_student::find($id);
        $info_referral = Referral::where('id_user_student', $id)
                         ->orderBy('created_at', 'desc')
                         ->first();

        return view('psycho.showDetailsReferral', compact('groups', 'degrees', 'info_student', 'info_referral'));
    }

    // Editar la remisión del estudiante
    public function update_details_referral(Request $request, string $id) {
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name' => 'required|string',
            'last_name' => 'required|string',
            'degree' => 'required|exists:degrees,id',
            'group' => 'required|exists:groups,id',
            'age' => 'required|integer|min:0',
            'reason_referral' => 'required|string',
            'observation' => 'required|string',
            'strategies' => 'required|string',
        ]);
    
        $student = Users_student::find($id);
    
        if (!$student) {
            return redirect()->back()->with('error', 'Estudiante no encontrado.');
        }
    
        $referral = Referral::where('id_user_student', $id)
                            ->orderBy('created_at', 'desc')
                            ->first();
    
        $new_dates_student = [
            'number_documment' => $request->number_documment, 
            'name' => $request->name,
            'last_name' => $request->last_name,
            'id_degree' => $request->degree,
            'id_group' => $request->group,
            'age' => $request->age,
        ];
    
        $new_dates_referral = [
            'reason' => $request->reason_referral,
            'observation' => $request->observation,
            'strategies' => $request->strategies,
        ];
    
        // Verificar cambios en el estudiante
        $huboCambiosStudent = collect($new_dates_student)->diffAssoc($student->toArray())->isNotEmpty();
    
        // Verificar cambios en la remisión
        $huboCambiosReferral = $referral ? collect($new_dates_referral)->diffAssoc($referral->toArray())->isNotEmpty() : false;
    
        // Verificar cambios en el grado del estudiante
        $gradoActual = $student->id_degree;
        $nuevoGrado = $request->degree; // Obtén el nuevo grado del request
        $huboCambioGrado = $gradoActual != $nuevoGrado;
    
        // Actualizar datos del estudiante si hubo cambios
        if ($huboCambiosStudent) {
            $student->update($new_dates_student);
        }
    
        // Actualizar datos de la remisión si hubo cambios
        if ($huboCambiosReferral || $huboCambioGrado) {
            if ($referral) {
                // Obtener el nombre del grado desde el modelo Degree
                $nombreGrado = Degree::find($request->degree)?->degree ?? null;

                if ($nombreGrado) {
                    $referral->update(array_merge($new_dates_referral, [
                        'course' => $nombreGrado, // Actualiza el campo course con el nombre del grado
                    ]));
                } else {
                    return redirect()->back()->with('error', 'No se pudo encontrar el nombre del grado para actualizar el campo course.');
                }
            } else {
                Referral::create(array_merge($new_dates_referral, [
                    'id_user_student' => $id,
                    'course' => $nuevoGrado, // Incluye el grado al crear una nueva remisión
                ]));
            }
        }
    
        // Respuesta según si hubo cambios o no
        if ($huboCambiosStudent || $huboCambiosReferral || $huboCambioGrado) {
            return redirect()->back()->with('success', 'Remisión editada correctamente.');
        } else {
            return redirect()->back()->with('info', 'No hubo cambios en la remisión.');
        }
    }
    
    // Visualizar la vista donde se creara el informe por parte de la psicoorientadora.
    public function report_student(string $id) {
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $states = State::whereIn('state', ['en espera', 'descartado'])->get();
        $info_student = Users_student::find($id);

        return view('psycho.addReportStudent', compact('groups', 'degrees', 'states', 'info_student'));
    }

    // Crear el informe por parte de la psicoorientadora y editar datos del estudiante si hace falta.
    public function store_report_student(Request $request, string $id) {
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name' => 'required|string',
            'last_name' => 'required|string',
            'degree' => 'required|exists:degrees,id',
            'group' => 'required|exists:groups,id',
            'age' => 'required',
            'state' => 'required|exists:states,id',
            'title_report' => 'required|string',
            'reason_inquiry' => 'required',
            'recomendations' => 'required',
        ]);

        // Datos actuales del estudiate
        $current_dates_student = Users_student::find($id);

        // Nuevos datos del estudiante
        $new_dates_student = [
            'number_documment' => $request->number_documment,
            'name' => $request->name,
            'last_name' => $request->last_name,
            'id_degree' => $request->degree,
            'id_group' => $request->group,
            'age' => $request->age,
            'id_state' => $request->state,
        ];

        // Verificar cambios en el estudiante
        $huboCambiosStudent = collect($new_dates_student)->diffAssoc($current_dates_student->toArray())->isNotEmpty();

        $id_state = $request->state; // Obtiene el ID del estado enviado
        $state = State::find($id_state)?->state; // Encuentra el registro y obtiene el valor de `state`
        $id_sent_by = $current_dates_student->sent_by;
        $teacher = Users_teacher::find($id_sent_by);

        DB::beginTransaction();

        try {
            if ($huboCambiosStudent) {

                if ($state == 'descartado') {
                    $current_dates_student->update($new_dates_student);
                    Mail::to($teacher->email)->queue(new DiscardStudentMail($current_dates_student));
                }else {
                    // Verificar cambios en el grado o grupo del estudiante
                    $gradoActual = $current_dates_student->id_degree;
                    $nuevoGrado = $request->degree; // Obtén el nuevo grado del request
                    $huboCambioGrado = $gradoActual != $nuevoGrado;

                    if ($huboCambioGrado) {
                        $referral = Referral::where('id_user_student', $id)->orderBy('created_at', 'desc')->first();
                        $nombreGrado = Degree::find($request->degree)?->degree ?? null;
                        if ($referral && $nombreGrado) {
                            $referral->update(['course' => $nombreGrado]);
                        }
                    }

                    $current_dates_student->update($new_dates_student);
                }
                
            }

            $id_psico = Auth::id(); // Obtengo el id del psicologo que realiza el informe, osea el que esta autenticado.
            $id_group_student = Users_student::where('id', $id)->value('id_group'); // Obtengo el id del grupo al que pertenece el estudiante.
            $fullname_director_group = Users_teacher::where('group_director', $id_group_student)->first(); // Con el id del grupo obtenido anteriormente obtengo el registro del director del grupo.
            $director_name = $fullname_director_group ? $fullname_director_group->name . ' ' . $fullname_director_group->last_name : 'N/A'; // Obtengo el nombre y el apellido del director del grupo.
            $group = Group::find($request->group); // Obtengo el Grupo se esta dejando en el input del formulario.

            $report = new Psychoorientation();
            $report->psychologist_writes = $id_psico;
            $report->id_user_student = $id;
            $report->age_student = $request->age;
            $report->group_student = $group->group; // Gracias a lña relacion que he hecho puedo guardar el nombre del grupo directamente.
            $report->director_group_student = $director_name;
            $report->title_report = $request->title_report;
            $report->reason_inquiry = $request->reason_inquiry;
            $report->recomendations = $request->recomendations;
            $report->save();

            DB::commit();
            return redirect()->route('index.student.remitted.psico')->with('success', 'Informe agregado correctamente.');
        }catch (\Exception $e) {
            DB::rollback();
            // Registra o muestra el error para depuración
            return redirect()->back()->with('error', 'Hubo problemas en el proceso. Intentelo nuevamente.');
        }

    }

    // Visualizar historial del estudiante.
    public function show_student_history(string $id) {
        $referrals = Referral::where('id_user_student', $id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        $reports = Psychoorientation::where('id_user_student', $id)
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(15);

        $student = Users_student::find($id);

        return view('psycho.studentHistory', compact('referrals', 'reports', 'student'));
    }

    // Visualizar detalles de la remision seleccionada en el historial del estudiante
    public function history_details_referral(string $id) {

        $referral = Referral::find($id);
        $id_student = $referral->id_user_student;
        $student = Users_student::find($id_student);

        return view('psycho.detailsHistory.referral', compact('referral', 'student'));
    }

    // Visualizar detalles del informe seleccionado en el historial del estudiante
    public function history_details_report(string $id) {
        $report = Psychoorientation::find($id);
        $id_student = $report->id_user_student;
        $student = Users_student::find($id_student);

        return view('psycho.detailsHistory.report', compact('report', 'student'));
    }

    // Actualizar o editar informacion de la remision seleccionada en el historial del estudiante.
    public function update_history_details_referral(Request $request, string $id){
        $request->validate([
            'reason_referral' => 'required|string',
            'observation' => 'required|string',
            'strategies' => 'required|string',
        ]);

        $current_referral = Referral::find($id);

        $new_referral = [
            'reason' => $request->reason_referral,
            'observation' => $request->observation,
            'strategies' => $request->strategies,
        ];

        // Verificar cambios en la remisión
        $huboCambiosReferral = collect($new_referral)->diffAssoc($current_referral->toArray())->isNotEmpty();

        if ($huboCambiosReferral) {
            $current_referral->update($new_referral);
            return redirect()->back()->with('success', 'Remisón actualizada correctamente.');
        }else {
            return redirect()->back()->with('info', 'No hubo cambios en la remisión.');
        }
           
    }

    // Actualizar o editar informacion del informe seleccionado en el historial del estudiante.
    public function update_history_details_report(Request $request, string $id){
        $request->validate([
            'reason_inquiry' => 'required|string',
            'recomendations' => 'required|string',
        ]);

        $current_report = Psychoorientation::find($id);

        $new_report = [
            'reason_inquiry' => $request->reason_inquiry,
            'recomendations' => $request->recomendations,
        ];

        $huboCambiosReport = collect($new_report)->diffAssoc($current_report->toArray())->isNotEmpty();

        if ($huboCambiosReport) {
            $current_report->update($new_report);
            return redirect()->back()->with('success', 'Informe actualizado correctamente.');
        }else {
            return redirect()->back()->with('info', 'No hubo cambios en el informe.');
        }
        
    }
    
    // Visualizar estudiantes en estado de espera.
    public function waiting_students(Request $request) {
        $id_psico = Auth::id(); // Obtengo el id de la psicoorientadora autentificada.
        $load_degree = Users_load_degree::where('id_user', $id_psico)->pluck('id_degree')->toArray();

        // Obtener los estudiantes cuyo id_state está en los estados obtenidos
        $query = Users_student::whereHas('states', function ($q) {
            $q->whereIn('state', ['en espera']);
        })->whereIn('id_degree', $load_degree);

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

        $periods = Period::all();

        return view('psycho.waitingStudent', compact('students', 'periods'));
    }

    // Aceptar estudiante para entarar en proceso PIAR
    public function accept_student_to_piar(Request $request)
    {
        $request->validate([
            'studentId' => 'required|int',
            'activation_period' => 'required|exists:periods,id',
        ]);

        $id_student = $request->studentId;

        // Buscar el estudiante
        $student = Users_student::with(['degree', 'group'])->find($id_student);

        if (!$student) {
            return redirect()->back()->with('error', 'Estudiante no encontrado.');
        }

        // Buscar el estado "en PIAR"
        $state = State::where('state', 'en PIAR')->value('id'); // Usa `value` para obtener un único valor

        if (!$state) {
            return redirect()->back()->with('error', 'Estado "en PIAR" no encontrado.');
        }

        $id_group_student = $student->id_group;

        // Obtener los IDs de los docentes relacionados con el grupo del estudiante
        $id_teachers_teachers = Users_load_group::where('id_group', $id_group_student)
            ->pluck('id_user_teacher') // Cambia 'id_teacher' al nombre correcto de la columna que contiene el ID del docente
            ->toArray();

        // Obtener los IDs de los usuarios docentes correspondientes
        $teachers = Users_teacher::whereIn('id', $id_teachers_teachers)->get();

        DB::beginTransaction();
        try {
            // Actualizar el estado del estudiante
            $student->update([
                'id_state' => $state,
                'activation_period' => $request->activation_period
            ]);

            // Enviar correo a cada docente
            foreach ($teachers as $teacher) {
                if ($teacher->email) { // Verifica si el correo del docente existe
                    Mail::to($teacher->email)->queue(new AcceptStudentMail($student)); // Pasar $student al mail
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'El estudiante ha sido aceptado para ingresar al proceso PIAR.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Hubo problemas en el proceso, intentelo nuevamente.');
        }
    }


    // Desacrtar estudiante que esta en proceso, no es aceptado para el proceso PIAR.
    public function discard_student(string $id){
        // Buscar el estudiante
        $student = Users_student::find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'Estudiante no encontrado.');
        }

        // Buscar el estado "en PIAR"
        $state = State::where('state', 'descartado')->value('id'); // Usa `value` para obtener un único valor

        if (!$state) {
            return redirect()->back()->with('error', 'Estado "descartado" no encontrado.');
        }

        $id_sent_by = $student->sent_by;
        $teacher = Users_teacher::find($id_sent_by);

        DB::beginTransaction();

        try {

            // Actualizar el estado del estudiante
            $student->update([
                'id_state' => $state,
            ]);

            Mail::to($teacher->email)->queue(new DiscardStudentMail($student)); // Pasar $student al mail

            DB::commit();

            return redirect()->back()->with('info', 'El estudiante ha sido descartado.');

        }catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Hubo problemas en el proceso, intentelo nuevamente.');
        } 
    }

}