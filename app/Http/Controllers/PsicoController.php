<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Group;
use App\Models\Referral;
use App\Models\Users_load_degree;
use App\Models\Users_student;
use Auth;
use Illuminate\Http\Request;

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
            'degree' => 'required',
            'group' => 'required',
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
    
        // Verificar cambios en el grado o grupo del estudiante
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
    
}
