<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Group;
use App\Models\Psychoorientation;
use App\Models\Referral;
use App\Models\State;
use App\Models\Users_load_degree;
use App\Models\Users_student;
use App\Models\Users_teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PsicoController extends Controller
{
    /* =====================================================
     | MÉTODO GENERAL PARA FILTRAR POR ESTADO
     ===================================================== */
    private function studentsByState(Request $request, string $state, string $label, string $routeName)
    {
        $id_psico = Auth::id();

        $load_degree = Users_load_degree::where('id_user', $id_psico)
            ->pluck('id_degree')
            ->toArray();

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
                  ->orWhereRaw("CONCAT(name,' ',last_name) LIKE ?", ["%$search%"]);
            });
        }

        $students = $query
            ->orderBy('name')
            ->orderBy('last_name')
            ->paginate(15);

        return view('psycho.listStudentsByState', [
            'students'   => $students,
            'stateLabel' => $label,
            'route'      => route($routeName),
        ]);
    }

    /* =====================================================
     | LISTADOS
     ===================================================== */

    // ✅ ACTIVO: TODOS los estudiantes (sin importar estado)
    public function index_students_active_psico(Request $request)
    {
        $id_psico = Auth::id();

        $load_degree = Users_load_degree::where('id_user', $id_psico)
            ->pluck('id_degree')
            ->toArray();

        $query = Users_student::whereIn('id_degree', $load_degree);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('last_name', 'LIKE', "%$search%")
                  ->orWhere('number_documment', 'LIKE', "%$search%")
                  ->orWhereRaw("CONCAT(name,' ',last_name) LIKE ?", ["%$search%"]);
            });
        }

        $students = $query
            ->orderBy('name')
            ->orderBy('last_name')
            ->paginate(15);

        return view('psycho.listStudentsByState', [
            'students'   => $students,
            'stateLabel' => 'Activos',
            'route'      => route('psico.students.active'),
        ]);
    }

    public function index_students_piar_psico(Request $request)
    {
        return $this->studentsByState($request, 'en PIAR', 'En PIAR', 'psico.students.piar');
    }

    public function index_students_dua_psico(Request $request)
    {
        return $this->studentsByState($request, 'en DUA', 'En DUA', 'psico.students.dua');
    }

    public function index_student_remitted_psico(Request $request)
    {
        $id_psico = Auth::id();

        $load_degree = Users_load_degree::where('id_user', $id_psico)
            ->pluck('id_degree')
            ->toArray();

        $query = Users_student::whereHas('states', function ($q) {
                $q->where('state', 'activo');
            })
            ->whereIn('id_degree', $load_degree);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('last_name', 'LIKE', "%$search%")
                  ->orWhere('number_documment', 'LIKE', "%$search%");
            });
        }

        $students = $query
            ->orderBy('name')
            ->orderBy('last_name')
            ->paginate(15);

        return view('psycho.listRemitted', compact('students'));
    }

    /* =====================================================
     | DETALLES DE REMISIÓN
     ===================================================== */

    public function detailsReferral(string $id)
    {
        $groups  = Group::orderByRaw('CAST(`group` AS UNSIGNED)')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED)')->get();

        $info_student = Users_student::findOrFail($id);
        $info_referral = Referral::where('id_user_student', $id)
            ->latest()
            ->first();

        return view('psycho.showDetailsReferral', compact(
            'groups',
            'degrees',
            'info_student',
            'info_referral'
        ));
    }

    public function update_details_referral(Request $request, string $id)
    {
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name'             => 'required|string',
            'last_name'        => 'required|string',
            'degree'           => 'required|exists:degrees,id',
            'group'            => 'required|exists:groups,id',
            'age'              => 'required|integer|min:0',
            'reason_referral'  => 'required|string',
            'observation'      => 'required|string',
            'strategies'       => 'required|string',
        ]);

        $student  = Users_student::findOrFail($id);
        $referral = Referral::where('id_user_student', $id)->latest()->first();

        $student->update([
            'number_documment' => $request->number_documment,
            'name'             => $request->name,
            'last_name'        => $request->last_name,
            'id_degree'        => $request->degree,
            'id_group'         => $request->group,
            'age'              => $request->age,
        ]);

        if ($referral) {
            $referral->update([
                'reason'      => $request->reason_referral,
                'observation' => $request->observation,
                'strategies'  => $request->strategies,
                'course'      => Degree::find($request->degree)->degree,
            ]);
        }

        return back()->with('success', 'Remisión actualizada correctamente.');
    }

    /* =====================================================
     | INFORMES PSICOLÓGICOS
     ===================================================== */

    public function report_student(string $id)
    {
        $groups  = Group::orderByRaw('CAST(`group` AS UNSIGNED)')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED)')->get();
        $states  = State::whereIn('state', ['activo', 'en PIAR', 'en DUA'])->get();

        $info_student = Users_student::findOrFail($id);

        return view('psycho.addReportStudent', compact(
            'groups',
            'degrees',
            'states',
            'info_student'
        ));
    }

    public function store_report_student(Request $request, string $id)
    {
        $request->validate([
            'number_documment' => 'required|digits_between:1,20|unique:users_students,number_documment,' . $id,
            'name'             => 'required|string',
            'last_name'        => 'required|string',
            'degree'           => 'required|exists:degrees,id',
            'group'            => 'required|exists:groups,id',
            'age'              => 'required|integer',
            'state'            => 'required|exists:states,id',
            'title_report'     => 'required|string',
            'reason_inquiry'   => 'required|string',
            'recomendations'   => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $student = Users_student::findOrFail($id);

            $student->update([
                'number_documment' => $request->number_documment,
                'name'             => $request->name,
                'last_name'        => $request->last_name,
                'id_degree'        => $request->degree,
                'id_group'         => $request->group,
                'age'              => $request->age,
                'id_state'         => $request->state,
            ]);

            $group = Group::findOrFail($request->group);
            $director = Users_teacher::where('group_director', $request->group)->first();

            Psychoorientation::create([
                'psychologist_writes'    => Auth::id(),
                'id_user_student'        => $id,
                'age_student'            => $request->age,
                'group_student'          => $group->group,
                'director_group_student' => $director
                    ? $director->name . ' ' . $director->last_name
                    : 'No asignado',
                'title_report'           => $request->title_report,
                'reason_inquiry'         => $request->reason_inquiry,
                'recomendations'         => $request->recomendations,
            ]);

            DB::commit();

            return redirect()
                ->route('index.student.remitted.psico')
                ->with('success', 'Informe guardado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* =====================================================
     | HISTORIAL DEL ESTUDIANTE
     ===================================================== */

    public function show_student_history(string $id)
    {
        $student = Users_student::findOrFail($id);

        $referrals = Referral::where('id_user_student', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $reports = Psychoorientation::where('id_user_student', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('psycho.studentHistory', compact(
            'student',
            'referrals',
            'reports'
        ));
    }
}
