<?php

namespace App\Http\Controllers;

use App\Models\Asignature;
use Illuminate\Http\Request;

class CreateAsignatureController extends Controller
{
    // Vista de Create asignature, se ve toda la lista de asignaturas, incluye todo el CRUD
    public function create_asignature(Request $request)
    {
        // Inicializa el query base
        $query = Asignature::orderBy('name_asignature', 'asc');

        // Aplica filtro de búsqueda si existe
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name_asignature', 'LIKE', '%' . $searchTerm . '%');
        }

        // Ejecuta la consulta
        $list_asignatures = $query->get(); // O usa paginate() si es necesario.

        // Retorna la vista con los resultados
        return view('academic.createAsignature', compact('list_asignatures'));
    }

    // Create Asignature, logica para crear un asignatura
    public function store_asignature(Request $request) {
        $request->validate([
            'asignature' => 'required|string|unique:asignatures,name_asignature',
        ]);

        try {
            $asignature = new Asignature();
            $asignature->name_asignature = ucfirst($request->asignature); // 'ucfirst' convierte la primera letra en mayuscula.
            $asignature->save();
    
            return redirect()->back()->with('success', 'Asignatura creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al crear la asignatura. Intente nuevamente.');
        }
    }

    // Udate Asignature, actualizar los datos de la asignatura
    public function update_asignature(Request $request)
    {
        // ID de la signatura
        $id = $request->input('asignatureId');

        // Busco la asignatura por el id
        $asignature = Asignature::find($id);

        if (!$asignature) {
            return redirect()->back()->with('error', 'Asignatura no encontrada.');
        }

        // Validación del request
        $request->validate([
            'asignature_edit' => 'unique:asignatures,name_asignature,' . $id,
        ]);

        // Verificacion si la asignatura ha cambiado
        if ($asignature->name_asignature !== ucfirst($request->asignature_edit)) {
            if ($request->asignature_edit === '' || $request->asignature_edit === null) {
                return redirect()->back()->with('info', 'No se ha asignado un valor al campo de asignatura.');
            } else {
                // Actualiza el grado
                $asignature->update([
                    'name_asignature' => ucfirst($request->asignature_edit),
                ]);

                return redirect()->back()->with('success', 'Asignatura actualizada correctamente.');
            }
        }

        // Si no hubo cambios
        return redirect()->back()->with('info', 'No hubo cambios en la actualización de la asignatura.');
    }

    // Delete Asignature, eliminar asignatura.
    public function destroy_asignature(string $id) {
        $asignature = Asignature::find($id);

        if (!$asignature) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }

        try {
            // Eliminar grado
            $asignature->delete();
            // Retorna una respuesta de éxito
            return redirect()->back()->with('success', 'Asignatura eliminada exitosamente.');
        } catch (\Exception $e) {
            // Captura cualquier error durante la eliminación
            return redirect()->back()->with('error', 'Hubo problemas al eliminar la asignatura, intentalo de nuevo.');
        }
    }
}
