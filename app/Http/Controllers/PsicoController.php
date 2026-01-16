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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class PsicoController extends Controller
{
    /**
     * MÉTODO PRIVADO: Centraliza la lógica de listados por estado para evitar errores.
     * He vuelto a las rutas de vistas originales: 'psycho.listStudentsByState'
     */
    private function studentsByState(Request $request, string $state, string $label, string $routeName)
    {
        $id_psico = Auth::id();
        $load_degree = Users_load_degree::where('id_user', $id_psico)->pluck('id_degree')->toArray();

        $query = Users_student::whereHas('states', function ($q) use ($state) {
                $q->where('state', $state);
            })
            ->whereIn('id_degree', $load_degree);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('last_name', 'LIKE', "%$search%")
                  ->orWhere('number_documment', 'LIKE', "%$search%")
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%$search%"]);
            });
        }

        $students = $query->orderBy('name')->orderBy('last_name')->paginate(15);

        return view('psycho.listStudentsByState', [ // Carpeta original restaurada
            'students'   => $students,
            'stateLabel' => $label,
            'route'      => route($routeName),
        ]);
    }

    // --- LISTADOS PRINCIPALES ---

    public function index_students_active_psico(Request $request)
    {
        return $this->studentsByState($request, 'activo', 'Activos', 'psico.students.active');
    }

    public function index_students_piar_psico(Request $request)
    {
        return $this->studentsByState($request, 'en PIAR', 'en PIAR', 'psico.students.piar');
    }

    public function index_students_dua_psico(Request $request)
    {
        return $this->studentsByState($request, 'en DUA', 'en DUA', 'psico.students.dua');
    }

    public function index_student_remitted_psico(Request $request) 
    {
        $id_psico = Auth::id();
        $load_degree = Users_load_degree::where('id_user', $id_psico)->pluck('id_degree')->toArray();

        $query = Users_student::whereHas('states', function ($q) {
            $q->whereIn('state', ['activo']);
        })->whereIn('id_degree', $load_degree);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('number_documment', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $students = $query->orderBy('name', 'asc')->orderBy('last_name', 'asc')->paginate(15);
        return view('psycho.listRemitted', compact('students'));
    }

    // --- GESTIÓN DE REMISIONES (EDITAR Y VER) ---

    public function detailsReferral(string $id) 
    {
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $info_student = Users_student::findOrFail($id);
        $info_referral = Referral::where('id_user_student', $id)->orderBy('created_at', 'desc')->first();

        return view('psycho.showDetailsReferral', compact('groups', 'degrees', 'info_student', 'info_referral'));
    }

    public function update_details_referral(Request $request, string $id) 
    {
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

        $student = Users_student::findOrFail($id);
        $referral = Referral::where('id_user_student', $id)->orderBy('created_at', 'desc')->first();
    
        $student->update([
            'number_documment' => $request->number_documment, 
            'name' => $request->name,
            'last_name' => $request->last_name,
            'id_degree' => $request->degree,
            'id_group' => $request->group,
            'age' => $request->age,
        ]);

        $nombreGrado = Degree::find($request->degree)->degree;

        if ($referral) {
            $referral->update([
                'reason' => $request->reason_referral,
                'observation' => $request->observation,
                'strategies' => $request->strategies,
                'course' => $nombreGrado,
            ]);
        } else {
            Referral::create([
                'id_user_student' => $id,
                'reason' => $request->reason_referral,
                'observation' => $request->observation,
                'strategies' => $request->strategies,
                'course' => $nombreGrado,
            ]);
        }
    
        return back()->with('success', 'Remisión editada correctamente.');
    }

    // --- INFORMES ---

    public function report_student(string $id) 
    {
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $states = State::whereIn('state', ['en PIAR', 'en DUA', 'activo'])->get();
        $info_student = Users_student::findOrFail($id);

        return view('psycho.addReportStudent', compact('groups', 'degrees', 'states', 'info_student'));
    }

    public function store_report_student(Request $request, string $id) 
    {
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name' => 'required|string', 'last_name' => 'required|string',
            'degree' => 'required|exists:degrees,id', 'group' => 'required|exists:groups,id',
            'age' => 'required', 'state' => 'required|exists:states,id',
            'title_report' => 'required|string', 'reason_inquiry' => 'required', 'recomendations' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $student = Users_student::findOrFail($id);
            $student->update([
                'number_documment' => $request->number_documment, 'name' => $request->name, 'last_name' => $request->last_name,
                'id_degree' => $request->degree, 'id_group' => $request->group, 'age' => $request->age, 'id_state' => $request->state,
            ]);

            $director = Users_teacher::where('group_director', $request->group)->first();
            $director_name = $director ? $director->name . ' ' . $director->last_name : 'N/A';
            $group = Group::find($request->group);

            Psychoorientation::create([
                'psychologist_writes' => Auth::id(),
                'id_user_student' => $id,
                'age_student' => $request->age,
                'group_student' => $group->group,
                'director_group_student' => $director_name,
                'title_report' => $request->title_report,
                'reason_inquiry' => $request->reason_inquiry,
                'recomendations' => $request->recomendations,
            ]);

            DB::commit();
            return redirect()->route('index.student.remitted.psico')->with('success', 'Informe agregado correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hubo problemas en el proceso.');
        }
    }

    // --- HISTORIAL ---

    public function show_student_history(string $id) 
    {
        $student = Users_student::findOrFail($id);
        $referrals = Referral::where('id_user_student', $id)->orderBy('created_at', 'desc')->paginate(15);
        $reports = Psychoorientation::where('id_user_student', $id)->orderBy('created_at', 'desc')->paginate(15);
        return view('psycho.studentHistory', compact('referrals', 'reports', 'student'));
    }

    public function history_details_referral(string $id) 
    {
        $referral = Referral::findOrFail($id);
        $student = Users_student::findOrFail($referral->id_user_student);
        return view('psycho.detailsHistory.referral', compact('referral', 'student'));
    }

    public function history_details_report(string $id) 
    {
        $report = Psychoorientation::findOrFail($id);
        $student = Users_student::findOrFail($report->id_user_student);
        return view('psycho.detailsHistory.report', compact('report', 'student'));
    }

    public function update_history_details_referral(Request $request, string $id)
    {
        $request->validate(['reason_referral' => 'required', 'observation' => 'required', 'strategies' => 'required']);
        $referral = Referral::findOrFail($id);
        $referral->update(['reason' => $request->reason_referral, 'observation' => $request->observation, 'strategies' => $request->strategies]);
        return back()->with('success', 'Remisión actualizada.');
    }

    public function update_history_details_report(Request $request, string $id)
    {
        $request->validate(['reason_inquiry' => 'required', 'recomendations' => 'required']);
        $report = Psychoorientation::findOrFail($id);
        $report->update(['reason_inquiry' => $request->reason_inquiry, 'recomendations' => $request->recomendations]);
        return back()->with('success', 'Informe actualizado.');
    }

    // --- PROCESO PIAR ---

    public function accept_student_to_piar(Request $request)
    {
        $request->validate(['studentId' => 'required|int', 'activation_period' => 'required|exists:periods,id']);
        $student = Users_student::with(['degree', 'group'])->findOrFail($request->studentId);
        $stateId = State::where('state', 'en PIAR')->value('id');

        DB::beginTransaction();
        try {
            $student->update(['id_state' => $stateId, 'activation_period' => $request->activation_period]);
            $teacherIds = Users_load_group::where('id_group', $student->id_group)->pluck('id_user_teacher');
            $teachers = Users_teacher::whereIn('id', $teacherIds)->get();

            foreach ($teachers as $teacher) {
                if ($teacher->email) Mail::to($teacher->email)->queue(new AcceptStudentMail($student));
            }

            DB::commit();
            return back()->with('success', 'El estudiante ha sido aceptado para PIAR.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error en el proceso.');
        }
    }
}