<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class CreateAreaController extends Controller
{
    // Vista de Create area, se ve toda la lista de areas, incluye todo el CRUD
    public function create_area(Request $request)
    {
        // Inicializa el query base
        $query = Area::orderBy('name_area', 'asc');

        // Aplica filtro de búsqueda si existe
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name_area', 'LIKE', '%' . $searchTerm . '%');
        }

        // Ejecuta la consulta
        $list_areas = $query->get(); // O usa paginate() si es necesario.

        // Retorna la vista con los resultados
        return view('academic.createArea', compact('list_areas'));
    }

    // Create Area, logica para crear un area
    public function store_area(Request $request) {
        $request->validate([
            'area' => 'required|string|unique:areas,name_area',
        ]);

        try {
            $area = new Area();
            $area->name_area = ucfirst($request->area); // 'ucfirst' convierte la primera letra en mayuscula.
            $area->save();
    
            return redirect()->back()->with('success', 'Area creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al crear el area. Intente nuevamente.');
        }
    }

    // Update Area, actualizar los datos de la area
    public function update_area(Request $request)
    {
        // ID de la area
        $id = $request->input('areaId');

        // Busco la area por el id
        $area = Area::find($id);

        if (!$area) {
            return redirect()->back()->with('error', 'Area no encontrada.');
        }

        // Validación del request
        $request->validate([
            'area_edit' => 'unique:areas,name_area,' . $id,
        ]);

        // Verificacion si la area ha cambiado
        if ($area->name_area !== ucfirst($request->area_edit)) {
            if ($request->area_edit === '' || $request->area_edit === null) {
                return redirect()->back()->with('info', 'No se ha asignado un valor al campo de area.');
            } else {
                // Actualiza el grado
                $area->update([
                    'name_area' => ucfirst($request->area_edit),
                ]);

                return redirect()->back()->with('success', 'Area actualizada correctamente.');
            }
        }

        // Si no hubo cambios
        return redirect()->back()->with('info', 'No hubo cambios en la actualización de la area.');
    }

    // Delete Area, eliminar area.
    public function destroy_area(string $id) {
        $area = Area::find($id);

        if (!$area) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }

        try {
            // Eliminar grado
            $area->delete();
            // Retorna una respuesta de éxito
            return redirect()->back()->with('success', 'Area eliminada exitosamente.');
        } catch (\Exception $e) {
            // Captura cualquier error durante la eliminación
            return redirect()->back()->with('error', 'Hubo problemas al eliminar la area, intentalo de nuevo.');
        }
    }
}
