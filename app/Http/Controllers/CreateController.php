<?php

namespace App\Http\Controllers;

use App\Models\Asignature;
use App\Models\Degree;
use App\Models\Group;
use App\Models\State;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;
use App\Models\Teachers_asignatures_group;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Hace pruebas para verificar algun dato

class CreateController extends Controller
{
    // Vista create user
    public function create_user() {

        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $asignatures = Asignature::orderBy('name_asignature', 'asc')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $roles = Role::whereNotIn('name', ['estudiante', 'coordinador'])->get();
        
        return view('academic.createUser', compact('groups', 'asignatures', 'degrees', 'roles'));
    }

    // Store user
    public function store_user(Request $request) {

        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $role = $request->role;
        $role_name = Role::where('id', $role)->first()->name;

        // Crear usuario si el rol es docente
        if ($role_name == 'docente') {
            $request->validate([
                'number_documment' => 'required|digits_between:1,20|unique:users_teachers',
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users_teachers',
                'subjects' => 'required|array',
                'subjects.*' => 'exists:asignatures,id',
                'groups' =>'required|array',
                'groups.*' => 'exists:groups,id',
                'group_director' => 'nullable||unique:users_teachers',
                'asignature_id' => 'required|array',
                'asignature_id.*' => 'exists:asignatures,id',
                'groups_asig' => 'required|array',
                'groups_asig.*' => 'required|array',
                'groups_asig.*.*' => 'required|integer',
            ]);

            // Verificar si cada grupo en groups_asig está también en groups
            foreach ($request->groups_asig as $asignature_id => $assigned_groups) {
                foreach ($assigned_groups as $group) {
                    // Verificamos si el grupo está en la lista de `groups` seleccionada
                    if (!in_array($group, $request->groups)) {
                        // Si encontramos un grupo no permitido, devolvemos error de inmediato
                        return redirect()->back()->with('error', 'No se ha podido guardar el registro, compruebe que los grupos designados a cada asignatura coincidan con los grupos a cargo del docente.');
                    }
                }
            }

            // Verificación aignaturas impartidas en grupos
            // Verifica si ya se está impartiendo una asignatura en determinado grupo.
            foreach ($request->asignature_id as $asignature_id) {
                if (isset($request->groups_asig[$asignature_id])) {
                    foreach ($request->groups_asig[$asignature_id] as $groupId) {
                        $exists = Teachers_asignatures_group::where('id_asignature', $asignature_id)
                                                            ->where('id_group', $groupId)
                                                            ->exists();
                        if ($exists) {
                            $name_asignature = Asignature::find($asignature_id)->name_asignature;
                            $name_group = Group::find($groupId)->group;
                            return redirect()->back()->with('error', '¡Error! La asignatura de ' . $name_asignature . ' ya es impartida en el grupo ' .  $name_group .' por otro docente');
                        }
                    }
                }
            }
            
            //Guardar el registro de usuario
            $user = new Users_teacher();
            $user->number_documment = $request->input('number_documment');
            $user->name = $request->input('name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->group_director = $request->input('group_director');
            $user->id_state = 2;
            $user->password = bcrypt($request->input('number_documment'));
            $user->assignRole($role_name);
            $user->save();

            // Guardar las asignaturas seleccionadas
            foreach ($request->subjects as $subject) {
                DB::table('users_load_asignatures')->insert([
                    'id_user_teacher' => $user->id,
                    'id_asignature' => $subject,
                ]);
            }

            // Guardar los grupos seleccionados en la tabla users_load_groups
            foreach ($request->groups as $group) {
                DB::table('users_load_groups')->insert([
                    'id_user_teacher' => $user->id,
                    'id_group' => $group,
                ]);
            }

            // ************* Guardar los datos en la tabla teachers_asignatures_groups **************/
            // Iterar sobre cada asignatura y sus respectivos grupos
            foreach ($request->asignature_id as $asignatureId) {
                if (isset($request->groups_asig[$asignatureId])) {
                    foreach ($request->groups_asig[$asignatureId] as $groupId) {
                        //Guardar en la base de datos
                        DB::table('teachers_asignatures_groups')->insert([
                            'id_teacher' => $user->id,
                            'id_asignature' => $asignatureId,
                            'id_group' => $groupId,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Usuario creado correctamente.');                          
        }

        //crear usuario si el rol es psicoorientador
        if ($role_name == 'psicoorientador') {
            $request->validate([
                'number_documment' => 'required|digits_between:1,20|unique:users_teachers',
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users_teachers',
                'load_degree' => 'required|array',
                'load_degree.*' => 'exists:asignatures,id',
            ]);

            foreach ($request->load_degree as $degree) {
                $exists = Users_load_degree::where('id_degree', $degree)->exists();
                if ($exists) {
                    return redirect()->back()->with('error', 'Existen grados que ya están asignados a otro/a psicoorientador/a.');
                }
            }

            //Guardar el registro de usuario
            $user = new Users_teacher();
            $user->number_documment = $request->input('number_documment');
            $user->name = $request->input('name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->id_state = 2;
            $user->password = bcrypt($request->input('number_documment'));
            $user->assignRole($role_name);
            $user->save();

            // Guardar los grados seleccionados
            foreach ($request->load_degree as $load_degree) {
                DB::table('users_load_degrees')->insert([
                    'id_user' => $user->id,
                    'id_degree' => $load_degree,
                ]);
            }

            return redirect()->back()->with('success', 'Usuario creado correctamente.');
        } 
        // Log::info('valor de ID Role: ' . json_encode($role));
        // return response()->json(['Role' => $role]);
    }

    // Listar usuarios (docentes y psicoorientadores).
    public function index_users(Request $request) {

        $query = Users_teacher::whereHas('roles', function($q) {
            $q->whereIn('name', ['docente', 'psicoorientador']);
        })
        ->whereHas('states', function($q) {
            $q->where('state', 'activo');
        });

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('number_documment', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $users = $query->with(['groups', 'asignatures'])
                ->orderBy('name', 'asc')
                ->orderBy('last_name', 'asc')
                ->paginate(15);

        $userRoles = [];

        foreach ($users as $user) {
            $roles = $user->roles ? $user->roles->pluck('name') : collect();
            $userRoles[$user->id] = $roles->all(); 
        }

        return view('academic.userList', compact('users', 'userRoles'));
    }

    // Vista de editar usuario (docentes y psicoorientadores).
    public function edit_user(string $id) {

        $user =  Users_teacher::findOrFail($id);
        $roles = Role::whereNotIn('name', ['estudiante', 'coordinador'])->get();
        $asignatures = Asignature::orderBy('name_asignature', 'asc')->get();
        $selectedAsignatures = $user->asignatures->map(function($asignature) {
            return [
                'id' => $asignature->id,
                'name' => $asignature->name_asignature
            ];
        })->toArray();
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $selectedGroups = $user->groups->pluck('id')->toArray();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $selectedDegrees = $user->load_degrees->pluck('id')->toArray();
        // Obtener los grupos relacionados con las asignaturas
        $groupsForAsignature = [];

        foreach ($selectedAsignatures as $asignature) {
            $groupsForSubjects = Teachers_asignatures_group::where('id_teacher', $user->id)
                        ->where('id_asignature', $asignature['id'])
                        ->join('groups', 'teachers_asignatures_groups.id_group', '=', 'groups.id') // Asegúrate de ajustar el nombre de la tabla si es diferente
                        ->select('groups.id', 'groups.group') // Selecciona los datos de los grupos
                        ->get();
        
            // Añade los grupos al array junto con el nombre de la asignatura
            $groupsForAsignature[] = [
                'asignature_id' => $asignature['id'],
                'groups' => $groupsForSubjects
            ];
        }

        // Log::info('valor de ID Role: ' . json_encode($groupsForAsignature));
        // return response()->json(['Asignaturas' => $groupsForAsignature]);

        return view('academic.userEdit', compact('user', 'roles', 'asignatures', 'selectedAsignatures', 'groups', 'selectedGroups', 'degrees', 'selectedDegrees', 'groupsForAsignature'));
    }

    // Editar usuarios (docentes y psicoorientadores)
    public function update_user(Request $request, string $id) {
        $user = Users_teacher::findOrFail($id);
        $datos_actuales = $user->toArray();
        $huboCambios = false;

        $role_user = $user->roles->first();
        $role_actual = $role_user ? $role_user->name : null;

        if ($role_actual == 'docente') {
            // Validar los datos de la solicitud
            $request->validate([
                'number_documment' => 'required|digits_between:1,20|unique:users_teachers,number_documment,' . $id,
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users_teachers,email,' . $id,
                'subjects' => 'required|array',
                'subjects.*' => 'exists:asignatures,id',
                'groups' =>'required|array',
                'groups.*' => 'exists:groups,id',
                'group_director' => 'nullable|unique:users_teachers,group_director,' . $id,
                // Esta parte de abajo de la validación es la que se esta añadiendo.
                'asignature_id' => 'required|array',
                'asignature_id.*' => 'exists:asignatures,id',
                'groups_asig' => 'required|array',
                'groups_asig.*' => 'required|array',
                'groups_asig.*.*' => 'required|integer',
            ]);

            // Verificar si hubo cambios en los datos del usuario
            $nuevos_datos = [
                'number_documment' => $request->number_documment,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'group_director' => $request->group_director,
            ];

            // Comparar los datos actuales con los nuevos
            foreach ($nuevos_datos as $key => $value) {
                if ($datos_actuales[$key] != $value) {
                    $huboCambios = true;
                    break; 
                }
            }

            // Obtener las asignaturas actuales
            $asignaturas_actuales = DB::table('users_load_asignatures')
                ->where('id_user_teacher', $id)
                ->pluck('id_asignature')
                ->toArray();

            // Obtener los grupos actuales
            $grupos_actuales = DB::table('users_load_groups')
                ->where('id_user_teacher', $id)
                ->pluck('id_group')
                ->toArray();

            // Obtener los datos actuales del usuario en la tabla `teachers_asignatures_groups`
            $datos_actuales_TAG = DB::table('teachers_asignatures_groups')
            ->where('id_teacher', $id)
            ->get()
            ->map(function($item) {
                return [
                    'id_asignature' => $item->id_asignature,
                    'id_group' => $item->id_group
                ];
            })
            ->toArray();

            // Comparar asignaturas y grupos actuales con los nuevos
            $asignaturas_nuevas = $request->subjects;
            $grupos_nuevos = $request->groups;
            // Organizar los nuevos datos del request para compararlos
            $nuevos_datos_TAG = [];
            foreach ($request->asignature_id as $asignatureId) {
                if (isset($request->groups_asig[$asignatureId])) {
                    foreach ($request->groups_asig[$asignatureId] as $groupId) {
                        $nuevos_datos_TAG[] = [
                            'id_asignature' => $asignatureId,
                            'id_group' => $groupId
                        ];
                    }
                }
            }

            // Verificar si hay cambios en asignaturas
            if (array_diff($asignaturas_actuales, $asignaturas_nuevas) || array_diff($asignaturas_nuevas, $asignaturas_actuales)) {
                $huboCambios = true;
            }

            // Verificar si hay cambios en grupos
            if (array_diff($grupos_actuales, $grupos_nuevos) || array_diff($grupos_nuevos, $grupos_actuales)) {
                $huboCambios = true;
            }

            if (array_diff_assoc($nuevos_datos_TAG, $datos_actuales_TAG) || array_diff_assoc($datos_actuales_TAG, $nuevos_datos_TAG)) {
                $huboCambios = true;
            }

            if ($huboCambios) {
                try {
                    DB::transaction(function () use ($id, $asignaturas_nuevas, $grupos_nuevos, $nuevos_datos_TAG) {
                        // Eliminar las asignaturas actuales
                        DB::table('users_load_asignatures')->where('id_user_teacher', $id)->delete();
                        
                        // Eliminar los grupos actuales
                        DB::table('users_load_groups')->where('id_user_teacher', $id)->delete();

                        // Eliminar datos de la tabla teachers_asignatures_groups
                        DB::table('teachers_asignatures_groups')->where('id_teacher', $id)->delete();
            
                        // Insertar las nuevas asignaturas
                        foreach ($asignaturas_nuevas as $subject) {
                            DB::table('users_load_asignatures')->insert([
                                'id_user_teacher' => $id,
                                'id_asignature' => $subject,
                            ]);
                        }
            
                        // Insertar los nuevos grupos
                        foreach ($grupos_nuevos as $group) {
                            DB::table('users_load_groups')->insert([
                                'id_user_teacher' => $id,
                                'id_group' => $group,
                            ]);
                        }

                        // Insertar los nuevos datos a la tabla teachers_asignatures_groups
                        foreach ($nuevos_datos_TAG as $new_date) {
                            DB::table('teachers_asignatures_groups')->insert([
                                'id_teacher' => $id,
                                'id_asignature' => $new_date['id_asignature'],
                                'id_group' => $new_date['id_group'],
                            ]);
                        };
                    });

                    // Actualizar los campos del usuario
                    $user->update($nuevos_datos);
            
                    return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
                } catch (\Exception $e) {
                    // Manejo del error
                    return redirect()->back()->with('error', 'Ocurrió un error al actualizar el usuario: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('info', 'No hubo cambios en la actualización del usuario.');
            }
        }

        if ($role_actual == 'psicoorientador') {
            // Validación
            $request->validate([
                'number_documment' => 'required|digits_between:1,20|unique:users_teachers,number_documment,' . $id,
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users_teachers,email,' . $id,
                'load_degree' => 'required|array',
                'load_degree.*' => 'exists:asignatures,id',
            ]);

            // verificar si hubo cambios
            $datos_nuevos = [
                'number_documment' => $request->number_documment,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ];

            // Comparar los datos actuales con los nuevos
            foreach ($datos_nuevos as $key => $value) {
                if ($datos_actuales[$key] != $value) {
                    $huboCambios = true;
                    break; 
                }
            }

            // Obtener las grados a cargo actuales
            $grados_actuales = DB::table('users_load_degrees')
            ->where('id_user', $id)
            ->pluck('id_degree')
            ->toArray();

            // Comparar asignaturas y grupos actuales con los nuevos
            $grados_nuevos = $request->load_degree;

            // Verificar si hay cambios
            if (array_diff($grados_actuales, $grados_nuevos) || array_diff($grados_nuevos, $grados_actuales)) {
                $huboCambios = true;
            }

            // Si hubo cambios, eliminar e insertar
            if ($huboCambios) {
                DB::transaction(function () use ($id, $grados_nuevos) {
                    // Eliminar grados actuales
                    DB::table('users_load_degrees')->where('id_user', $id)->delete();

                    // Insertar nuevos grados
                    foreach ($grados_nuevos as $grado) {
                        DB::table('users_load_degrees')->insert([
                            'id_user' => $id,
                            'id_degree' => $grado,
                        ]);
                    }
                });

                $user->update($datos_nuevos);

                return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
            } else {
                return redirect()->back()->with('info', 'No hubo cambios en la actualización del usuario.');
            }

        }

    }

    public function destroy_user(string $id) {
        // Obtener el usuario por ID
        $user = Users_teacher::findOrFail($id);

        // Obtener el estado 'bloqueado'
        $state = State::where('state', 'bloqueado')->firstOrFail();

        // Actualizar el estado del usuario
        $user->update([
            'group_director' => null,
            'id_state' => $state->id, // Asegúrate de actualizar con el ID del estado      
        ]);

        Teachers_asignatures_group::where('id_teacher', $id)->delete();
        Users_load_degree::where('id_user', $id)->delete();

        return redirect()->back()->with('error', 'El usuario se ha eliminado exitosamente.');
    }

}