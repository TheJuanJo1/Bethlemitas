<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Degree;
use App\Models\Group;
use App\Models\State;
use App\Models\Users_load_area;
use App\Models\Users_load_degree;
use App\Models\Users_load_group;
use App\Models\Users_teacher;
use App\Models\Teachers_areas_group;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Hace pruebas para verificar algun dato

class CreateController extends Controller
{
    // Vista create user
    public function create_user()
    {

        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $areas = Area::orderBy('name_area', 'asc')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $roles = Role::whereNotIn('name', ['estudiante', 'coordinador'])->get();

        return view('academic.createUser', compact('groups', 'areas', 'degrees', 'roles'));
    }

    // Store user
    public function store_user(Request $request)
    {

        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $role = $request->role;
        $role_name = Role::where('id', $role)->first()->name;
        $number_documment = $request->input('number_documment');
        $exist_user = Users_teacher::where('number_documment', $number_documment)->first();
        // Obtener el estado 'activo'
        $state = State::where('state', 'activo')->firstOrFail();

        // Crear usuario si el rol es docente
        if ($role_name == 'docente') {

            if ($exist_user) {
                $request->validate([
                    'email' => 'required|unique:users_teachers,email,' . $exist_user->id,
                    'areas' => 'required|array',
                    'areas.*' => 'exists:areas,id',
                    'groups' => 'required|array',
                    'groups.*' => 'exists:groups,id',
                    'group_director' => 'nullable|unique:users_teachers',
                    'area_id' => 'required|array',
                    'area_id.*' => 'exists:areas,id',
                    'groups_asig' => 'required|array',
                    'groups_asig.*' => 'required|array',
                    'groups_asig.*.*' => 'required|integer',
                ]);

                // $exist_user->update([
                //     'group_director' => $request->group_director,  // Actualizar el campo group_director
                //     'id_state' => $state->id,  // Actualizar estado a 'bloqueado'
                //     'email' => $request->email, // Actualizar email 
                // ]);

                // // Verificar si cada grupo en groups_asig está también en groups
                // foreach ($request->groups_asig as $area_id => $assigned_groups) {
                //     foreach ($assigned_groups as $group) {
                //         // Verificamos si el grupo está en la lista de `groups` seleccionada
                //         if (!in_array($group, $request->groups)) {
                //             // Si encontramos un grupo no permitido, devolvemos error de inmediato
                //             return redirect()->back()->with('error', 'No se ha podido guardar el registro, compruebe que los grupos designados a cada area coincidan con los grupos a cargo del docente.');
                //         }
                //     }
                // }

                // // Verificación areas impartidas en grupos
                // // Verifica si ya se está impartiendo una area en determinado grupo.
                // foreach ($request->area_id as $area_id) {
                //     if (isset($request->groups_asig[$area_id])) {
                //         foreach ($request->groups_asig[$area_id] as $groupId) {
                //             $exists = Teachers_areas_group::where('id_area', $area_id)
                //                 ->where('id_group', $groupId)
                //                 ->exists();
                //             if ($exists) {
                //                 $name_area = Area::find($area_id)->name_area;
                //                 $name_group = Group::find($groupId)->group;
                //                 return redirect()->back()->with('error', '¡Error! El area de ' . $name_area . ' ya es impartida en el grupo ' . $name_group . ' por otro docente');
                //             }
                //         }
                //     }
                // }

                // // Guardar las areas seleccionadas
                // foreach ($request->areas as $area) {
                //     DB::table('users_load_areas')->insert([
                //         'id_user_teacher' => $exist_user->id,
                //         'id_area' => $area,
                //     ]);
                // }

                // // Guardar los grupos seleccionados en la tabla users_load_groups
                // foreach ($request->groups as $group) {
                //     DB::table('users_load_groups')->insert([
                //         'id_user_teacher' => $exist_user->id,
                //         'id_group' => $group,
                //     ]);
                // }

                // // ************* Guardar los datos en la tabla teachers_areas_groups **************/
                // // Iterar sobre cada area y sus respectivos grupos
                // foreach ($request->area_id as $areaId) {
                //     if (isset($request->groups_asig[$areaId])) {
                //         foreach ($request->groups_asig[$areaId] as $groupId) {
                //             //Guardar en la base de datos
                //             DB::table('teachers_areas_groups')->insert([
                //                 'id_teacher' => $exist_user->id,
                //                 'id_area' => $areaId,
                //                 'id_group' => $groupId,
                //             ]);
                //         }
                //     }
                // }

                return redirect()->back()->with('success', 'Usuario creado correctamente.');


            } else {
                $request->validate([
                    'number_documment' => 'required|digits_between:1,11|unique:users_teachers',
                    'name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users_teachers',
                    'areas' => 'required|array',
                    'areas.*' => 'exists:areas,id',
                    'groups' => 'required|array',
                    'groups.*' => 'exists:groups,id',
                    'group_director' => 'nullable|unique:users_teachers',
                    'area_id' => 'required|array',
                    'area_id.*' => 'exists:areas,id',
                    'groups_asig' => 'required|array',
                    'groups_asig.*' => 'required|array',
                    'groups_asig.*.*' => 'required|integer',
                ]);

                // Verificar si cada grupo en groups_asig está también en groups
                foreach ($request->groups_asig as $area_id => $assigned_groups) {
                    foreach ($assigned_groups as $group) {
                        // Verificamos si el grupo está en la lista de `groups` seleccionada
                        if (!in_array($group, $request->groups)) {
                            // Si encontramos un grupo no permitido, devolvemos error de inmediato
                            return redirect()->back()->with('error', 'No se ha podido guardar el registro, compruebe que los grupos designados a cada area coincidan con los grupos a cargo del docente.');
                        }
                    }
                }

                // Verificación areas impartidas en grupos
                // Verifica si ya se está impartiendo una area en determinado grupo.
                foreach ($request->area_id as $area_id) {
                    if (isset($request->groups_asig[$area_id])) {
                        foreach ($request->groups_asig[$area_id] as $groupId) {
                            $exists = Teachers_areas_group::where('id_area', $area_id)
                                ->where('id_group', $groupId)
                                ->exists();
                            if ($exists) {
                                $name_area = Area::find($area_id)->name_area;
                                $name_group = Group::find($groupId)->group;
                                return redirect()->back()->with('error', '¡Error! El area de ' . $name_area . ' ya es impartida en el grupo ' . $name_group . ' por otro docente');
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
                $user->id_state = $state->id;
                $user->password = bcrypt($request->input('number_documment'));
                $user->assignRole($role_name);
                $user->save();

                // Guardar las areas seleccionadas
                foreach ($request->areas as $area) {
                    DB::table('users_load_areas')->insert([
                        'id_user_teacher' => $user->id,
                        'id_area' => $area,
                    ]);
                }

                // Guardar los grupos seleccionados en la tabla users_load_groups
                foreach ($request->groups as $group) {
                    DB::table('users_load_groups')->insert([
                        'id_user_teacher' => $user->id,
                        'id_group' => $group,
                    ]);
                }

                // ************* Guardar los datos en la tabla teachers_areas_groups **************/
                // Iterar sobre cada area y sus respectivos grupos
                foreach ($request->area_id as $areaId) {
                    if (isset($request->groups_asig[$areaId])) {
                        foreach ($request->groups_asig[$areaId] as $groupId) {
                            //Guardar en la base de datos
                            DB::table('teachers_areas_groups')->insert([
                                'id_teacher' => $user->id,
                                'id_area' => $areaId,
                                'id_group' => $groupId,
                            ]);
                        }
                    }
                }

                return redirect()->back()->with('success', 'Usuario creado correctamente.');
            }
        }

        //crear usuario si el rol es psicoorientador
        if ($role_name == 'psicoorientador') {

            if ($exist_user) {
                $request->validate([
                    'email' => 'required|unique:users_teachers,email,' . $exist_user->id,
                    'load_degree' => 'required|array',
                    'load_degree.*' => 'exists:degrees,id',
                ]);

                $exist_user->update([
                    'id_state' => $state->id,  // Actualizar estado a 'bloqueado'
                    'email' => $request->email, // Actualizar email
                ]);

                foreach ($request->load_degree as $degree) {
                    $exists = Users_load_degree::where('id_degree', $degree)->exists();
                    if ($exists) {
                        return redirect()->back()->with('error', 'Existen grados que ya están asignados a otro/a psicoorientador/a.');
                    }
                }

                // Guardar los grados seleccionados
                foreach ($request->load_degree as $load_degree) {
                    DB::table('users_load_degrees')->insert([
                        'id_user' => $exist_user->id,
                        'id_degree' => $load_degree,
                    ]);
                }

                return redirect()->back()->with('success', 'Usuario creado correctamente.');

            } else {
                $request->validate([
                    'number_documment' => 'required|digits_between:1,11|unique:users_teachers',
                    'name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users_teachers',
                    'load_degree' => 'required|array',
                    'load_degree.*' => 'exists:degrees,id',
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
                $user->id_state = 1;
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
        }
        // Log::info('valor de ID Role: ' . json_encode($role));
        // return response()->json(['Role' => $role]);
    }

    // Listar usuarios (docentes y psicoorientadores).
    public function index_users(Request $request)
    {
        $query = Users_teacher::whereHas('roles', function ($q) {
            $q->whereIn('name', ['docente', 'psicoorientador']);
        })
        ->whereHas('state', function ($q) {
            $q->where('state', 'activo');
        });

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('number_documment', 'LIKE', "%{$searchTerm}%")
                ->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"])
                ->orWhereHas('areas', function ($q) use ($searchTerm) {
                    $q->where('name_area', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        $users = $query->with(['groups', 'areas', 'roles', 'state'])
            ->orderBy('name')
            ->orderBy('last_name')
            ->paginate(15);

        $userRoles = $users->mapWithKeys(fn ($user) => [
            $user->id => $user->roles->pluck('name')->all()
        ]);

        return view('academic.userList', compact('users', 'userRoles'));
    }


    // Vista de editar usuario (docentes y psicoorientadores).
    public function edit_user(string $id)
    {

        $user = Users_teacher::findOrFail($id);
        $roles = Role::whereNotIn('name', ['estudiante', 'coordinador'])->get();
        $areas = Area::orderBy('name_area', 'asc')->get();
        $selectedAreas = $user->areas->map(function ($area) {
            return [
                'id' => $area->id,
                'name' => $area->name_area
            ];
        })->toArray();
        $groups = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $selectedGroups = $user->groups->pluck('id')->toArray();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $selectedDegrees = $user->loadDegrees()->pluck('id_degree')->toArray();
        // Obtener los grupos relacionados con las areas
        $groupsForArea = [];

        foreach ($selectedAreas as $area) {
            $groupsForThisArea = Teachers_areas_group::where('id_teacher', $user->id)
                ->where('id_area', $area['id'])
                ->join('groups', 'teachers_areas_groups.id_group', '=', 'groups.id')
                ->select('groups.id', 'groups.group')
                ->get()
                ->toArray();

            $groupsForArea[] = [
                'area_id' => $area['id'],
                'groups' => $groupsForThisArea
            ];
        }

        // Log::info('valor de ID Role: ' . json_encode($groupsForArea));
        // return response()->json(['Areas' => $groupsForArea]);

        return view('academic.userEdit', compact('user', 'roles', 'areas', 'selectedAreas', 'groups', 'selectedGroups', 'degrees', 'selectedDegrees', 'groupsForArea'));
    }

    // Editar usuarios (docentes y psicoorientadores)
    public function update_user(Request $request, string $id)
    {
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
                'areas' => 'required|array',
                'areas.*' => 'exists:areas,id',
                'groups' => 'required|array',
                'groups.*' => 'exists:groups,id',
                'group_director' => 'nullable|unique:users_teachers,group_director,' . $id,
                'area_id' => 'required|array',
                'area_id.*' => 'exists:areas,id',
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

            // Obtener las areas actuales
            $areas_actuales = DB::table('users_load_areas')
                ->where('id_user_teacher', $id)
                ->pluck('id_area')
                ->toArray();

            // Obtener los grupos actuales
            $grupos_actuales = DB::table('users_load_groups')
                ->where('id_user_teacher', $id)
                ->pluck('id_group')
                ->toArray();

            // Obtener los datos actuales del usuario en la tabla `teachers_areas_groups`
            $datos_actuales_TAG = DB::table('teachers_areas_groups')
                ->where('id_teacher', $id)
                ->get()
                ->map(function ($item) {
                    return [
                        'id_area' => $item->id_area,
                        'id_group' => $item->id_group
                    ];
                })
                ->toArray();

            // Comparar areas y grupos actuales con los nuevos
            $areas_nuevas = $request->areas;
            $grupos_nuevos = $request->groups;
            // Organizar los nuevos datos del request para compararlos
            $nuevos_datos_TAG = [];
            foreach ($request->area_id as $areaId) {
                if (isset($request->groups_asig[$areaId])) {
                    foreach ($request->groups_asig[$areaId] as $groupId) {
                        $nuevos_datos_TAG[] = [
                            'id_area' => $areaId,
                            'id_group' => $groupId
                        ];
                    }
                }
            }

            // Verificar si cada grupo en groups_asig está también en groups
            foreach ($request->groups_asig as $area_id => $assigned_groups) {
                foreach ($assigned_groups as $group) {
                    // Verificamos si el grupo está en la lista de `groups` seleccionada
                    if (!in_array($group, $request->groups)) {
                        // Si encontramos un grupo no permitido, devolvemos error de inmediato
                        return redirect()->back()->with('error', 'No se ha podido actualizar el registro, compruebe que los grupos designados a cada area coincidan con los grupos a cargo del docente.');
                    }
                }
            }

            // Verificación areas impartidas en grupos
            // Verifica si ya se está impartiendo una area en determinado grupo.
            foreach ($request->area_id as $area_id) {
                if (isset($request->groups_asig[$area_id])) {
                    foreach ($request->groups_asig[$area_id] as $groupId) {
                        $exists = Teachers_areas_group::where('id_area', $area_id)
                            ->where('id_group', $groupId)
                            ->where('id_teacher', '!=', $id)
                            ->exists();
                        if ($exists) {
                            $name_area = Area::find($area_id)->name_area;
                            $name_group = Group::find($groupId)->group;
                            return redirect()->back()->with('error', '¡Error! La area de ' . $name_area . ' ya es impartida en el grupo ' . $name_group . ' por otro docente');
                        }
                    }
                }
            }

            // Aplanar los arrays antes de hacer la comparación
            $nuevos_datos_TAG_flat = array_map(function ($item) {
                return $item['id_area'] . '-' . $item['id_group'];  // Combinar id_area y id_group en un solo valor
            }, $nuevos_datos_TAG);

            $datos_actuales_TAG_flat = array_map(function ($item) {
                return $item['id_area'] . '-' . $item['id_group'];  // Combinar id_area y id_group en un solo valor
            }, $datos_actuales_TAG);

            // Verificar si hay cambios en areas
            if (array_diff($areas_actuales, $areas_nuevas) || array_diff($areas_nuevas, $areas_actuales)) {
                $huboCambios = true;
            }

            // Verificar si hay cambios en grupos
            if (array_diff($grupos_actuales, $grupos_nuevos) || array_diff($grupos_nuevos, $grupos_actuales)) {
                $huboCambios = true;
            }

            // Comparar los datos de areas y grupos de teachers_areas_groups
            if (array_diff($nuevos_datos_TAG_flat, $datos_actuales_TAG_flat) || array_diff($datos_actuales_TAG_flat, $nuevos_datos_TAG_flat)) {
                $huboCambios = true;
            }

            if ($huboCambios) {
                try {
                    DB::transaction(function () use ($id, $areas_nuevas, $grupos_nuevos, $nuevos_datos_TAG) {
                        // Eliminar las areas actuales
                        DB::table('users_load_areas')->where('id_user_teacher', $id)->delete();

                        // Eliminar los grupos actuales
                        DB::table('users_load_groups')->where('id_user_teacher', $id)->delete();

                        // Eliminar datos de la tabla teachers_areas_groups
                        DB::table('teachers_areas_groups')->where('id_teacher', $id)->delete();

                        // Insertar las nuevas areas
                        foreach ($areas_nuevas as $area) {
                            DB::table('users_load_areas')->insert([
                                'id_user_teacher' => $id,
                                'id_area' => $area,
                            ]);
                        }

                        // Insertar los nuevos grupos
                        foreach ($grupos_nuevos as $group) {
                            DB::table('users_load_groups')->insert([
                                'id_user_teacher' => $id,
                                'id_group' => $group,
                            ]);
                        }

                        // Insertar los nuevos datos a la tabla teachers_areas_groups
                        foreach ($nuevos_datos_TAG as $new_data) {
                            DB::table('teachers_areas_groups')->insert([
                                'id_teacher' => $id,
                                'id_area' => $new_data['id_area'],
                                'id_group' => $new_data['id_group'],
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
                'load_degree.*' => 'exists:degrees,id',
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

            // Comparar areas y grupos actuales con los nuevos
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

    public function destroy_user(string $id)
    {
        // Obtener el usuario por ID
        $user = Users_teacher::findOrFail($id);

        // Obtener el estado 'bloqueado'
        $state = State::where('state', 'bloqueado')->firstOrFail();

        // Iniciar una transacción para garantizar integridad de los datos
        DB::transaction(function () use ($user, $state, $id) {
            // Actualizar el estado del usuario
            $user->update([
                'group_director' => null,  // Eliminar la relación de director de grupo
                'id_state' => $state->id,  // Actualizar estado a 'bloqueado'
            ]);

            // Eliminar relaciones en las tablas pivote
            Teachers_areas_group::where('id_teacher', $id)->delete();
            Users_load_area::where('id_user_teacher', $id)->delete();
            Users_load_group::where('id_user_teacher', $id)->delete();
            Users_load_degree::where('id_user', $id)->delete();
        });

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'El usuario se ha eliminado exitosamente.');
    }

}