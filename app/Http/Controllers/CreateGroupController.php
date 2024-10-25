<?php

namespace App\Http\Controllers;
use App\Models\Asignature;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CreateGroupController extends Controller
{   
    // Vista de create group.
    public function create_group(Request $request) {
        $asignatures = Asignature::all();
        $groups = Group::all();

        // Inicializa el query base
        $query = Group::with('asignatures')
              ->orderByRaw('CAST(`group` AS UNSIGNED), `group`'); // Ordena primero por número y luego por letra.

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('group', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        // Ejecuta la consulta sin paginación
        $list_groups = $query->get(); // Obtener todos los resultados.

        return view('academic.createGroup', compact('asignatures', 'groups', 'list_groups'));
    }

    // Funcion para añadir o creaar un nuevo grupo
    public function store_group(Request $request) {
        $request->validate([
            'group' => 'required|string|unique:groups',
            'asignatures' => 'required|array',
            'asignatures.*' => 'exists:asignatures,id',
        ]);

        $group = new Group();
        $group->group = $request->group;
        $group->save();

        // Guardar las asignaturas seleccionadas en la tabla pivote
        foreach ($request->asignatures as $asignature) {
            DB::table('subjects_receiveds')->insert([
                'id_group' => $group->id,
                'id_asignature' => $asignature,
            ]);
        }

        return redirect()->back()->with('success', 'Grupo creado exitosamente.');
    }

    //Funcion para editar grupo
    public function update_group(Request $request) {
        $id = $request->input('groupId');
        
        $group = Group::findOrFail($id);
        $current_group = $group->group;
        $huboCambios = false;

        $request->validate([
            'grupo_edit' => 'required|string|unique:groups,group,' . $id,
            'asignaturas_edit' => 'required|array',
            'asignaturas_edit.*' => 'exists:asignatures,id',
        ]);

        //Verificar si hubo cambios
        $new_group = $request->grupo_edit;

        // Comparar los datos actuales con los nuevos
        if ($current_group != $new_group) {
            $huboCambios = true;
        }

        // Obtener las asignaturas actuales
        $current_asignatures = DB::table('subjects_receiveds')
        ->where('id_group', $id)
        ->pluck('id_asignature')
        ->toArray();

        //Comparar las asignaturas con las nuevas
        $new_asignatures = $request->input('asignaturas_edit');

        // Verificar si hay cambios en asignaturas
        if (array_diff($current_asignatures, $new_asignatures) || array_diff($new_asignatures, $current_asignatures)) {
            $huboCambios = true;
        }

        if ($huboCambios) {
            try {
                DB::transaction(function () use ($id, $new_asignatures, $new_group, $group) {
                    // Eliminar las asignaturas actuales
                    DB::table('subjects_receiveds')->where('id_group', $id)->delete();

                    // Insertar las nuevas asignaturas
                    foreach ($new_asignatures as $asignature) {
                        DB::table('subjects_receiveds')->insert([
                            'id_group' => $id,  
                            'id_asignature' => $asignature,
                        ]);
                    }   
                });

                // Actualizar el campo del grupo
                $group->group = $new_group;
                $group->save();

                return redirect()->back()->with('success', 'Grupo actualizado correctamente.');
            } catch (\Exception $e) {
                // Manejo del error
                return redirect()->back()->with('error', 'Ocurrió un error al actualizar el grupo: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('info', 'No hubo cambios en la actualización del grupo.');
        }
    }

    // Funcion para eliminar grupo.
    public function destroy_group(string $id) {
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
