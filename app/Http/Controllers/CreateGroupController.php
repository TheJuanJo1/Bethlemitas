<?php

namespace App\Http\Controllers;
use App\Models\Area;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use App\Models\Users_teacher;

use Illuminate\Http\Request;

class CreateGroupController extends Controller
{
    // Vista de create group.
    public function create_group(Request $request)
    {
        $areas = Area::all();
        $groups = Group::all();

        // Inicializa el query base
        $query = Group::with(['areas', 'teachers'])
            ->orderByRaw('CAST(`group` AS UNSIGNED), `group`'); // Ordena primero por número y luego por letra.

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('group', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Ejecuta la consulta sin paginación
        $list_groups = $query->get(); // Obtener todos los resultados.

        $teachers = Users_teacher::orderBy('name')->get();


        return view('academic.createGroup', compact('areas', 'groups', 'list_groups', 'teachers'));
    }

    // Funcion para añadir o creaar un nuevo grupo
    public function store_group(Request $request)
    {
        $request->validate([
            'group' => 'required|string|unique:groups',
        ]);

        $group = new Group();
        $group->group = $request->group;
        $group->save();

        return redirect()->back()->with('success', 'Grupo creado exitosamente.');
    }

    //Funcion para editar grupo
    public function update_group(Request $request)
    {

        // ID del grupo
        $id = $request->input('groupId');

        // Buscamos el grupo por ID
        $group = Group::findOrFail($id);

        // Validacion del request
        $request->validate([
            'grupo_edit' => 'required|string|unique:groups,group,' . $id,
        ]);

        
        if ($group->group === ucfirst($request->grupo_edit)) {
            return redirect()->back()->with('info', 'No hubo cambios en la actualización del grupo.');
        }

        
        $group->update([
            'group' => ucfirst($request->grupo_edit),
        ]);

        return redirect()->back()->with('success', 'Grupo actualizado correctamente.');
    }
    // Funcion para eliminar grupo.
    public function destroy_group(string $id)
    {
        // Encuentra el grupo por ID
        $group = Group::find($id);

        // Verifica si el grupo existe
        if (!$group) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        try {
            // Elimina el grupo
            $group->delete();

            // Retorna una respuesta de éxito
            return redirect()->back()->with('success', 'Grupo eliminado exitosamente.');
        } catch (\Exception $e) {
            // Captura cualquier error durante la eliminación
            return redirect()->back()->with('error', 'Hubo problemas al eliminar el grupo, intentalo de nuevo.');
        }
    }
}
