<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;


class CreateDegreeController extends Controller
{
    public function create_degree(Request $request)
    {
        // Inicializa el query base
        // Este código extrae la parte numérica del campo degree antes del símbolo ° y la convierte a un entero para ordenarla correctamente.
        $query = Degree::orderByRaw("CAST(SUBSTRING_INDEX(degree, '°', 1) AS UNSIGNED) ASC");

        // Aplica filtro de búsqueda si existe
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('degree', 'LIKE', '%' . $searchTerm . '%');
        }

        // Ejecuta la consulta
        $list_degrees = $query->get(); // O usa paginate() si es necesario.

        // Retorna la vista con los resultados
        return view('academic.createDegree', compact('list_degrees'));
    }

    public function store_degree(Request $request) {
        $request->validate([
            'degree' => 'required|string|unique:degrees',
        ]);

        $degree = new Degree();
        $degree->degree = $request->degree;
        $degree->save();

        return redirect()->back()->with('success', 'Grado creado exitosamente.');
    }

    public function update_degree(Request $request)
    {
        // ID del grado 
        $id = $request->input('degreeId');

        // Busco el grado
        $degree = Degree::find($id);

        if (!$degree) {
            return redirect()->back()->with('error', 'Grado no encontrado.');
        }

        // Validación del request
        $request->validate([
            'degree_edit' => 'unique:degrees,degree,' . $id,
        ]);

        // Verificacion si el grado ha cambiado
        if ($degree->degree !== $request->degree_edit) {
            if ($request->degree_edit === '' || $request->degree_edit === null) {
                return redirect()->back()->with('info', 'No se ha asignado un valor al campo del grado.');
            } else {
                // Actualiza el grado
                $degree->update([
                    'degree' => $request->degree_edit,
                ]);

                return redirect()->back()->with('success', 'Grado actualizado correctamente.');
            }
        }

        // Si no hubo cambios
        return redirect()->back()->with('info', 'No hubo cambios en la actualización del grado.');
    }

    public function destroy_degree(string $id) {
        $degree = Degree::find($id);

        if (!$degree) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }

        try {
            // Eliminar grado
            $degree->delete();
            // Retorna una respuesta de éxito
            return redirect()->back()->with('success', 'Grado eliminado exitosamente.');
        } catch (\Exception $e) {
            // Captura cualquier error durante la eliminación
            return redirect()->back()->with('error', 'Hubo problemas al eliminar el grado, intentalo de nuevo.');
        }
    }

}


