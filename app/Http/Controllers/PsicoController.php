<?php

namespace App\Http\Controllers;

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
}
