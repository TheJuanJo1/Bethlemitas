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

class CreateController extends Controller
{
    /* ======================================================
     |  VISTA CREAR USUARIO
     ====================================================== */
    public function create_user()
    {
        $groups  = Group::orderByRaw('CAST(`group` AS UNSIGNED), `group`')->get();
        $areas   = Area::orderBy('name_area')->get();
        $degrees = Degree::orderByRaw('CAST(`degree` AS UNSIGNED), `degree`')->get();
        $roles   = Role::whereNotIn('name', ['estudiante'])->get();

        return view('academic.createUser', compact('groups', 'areas', 'degrees', 'roles'));
    }
    
    public function index_users(Request $request)
    {
        $query = Users_teacher::with(['roles', 'groups', 'areas', 'state'])
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['docente', 'psicoorientador', 'coordinador']);
            })
            ->where('id_state', 1); // ACTIVOS

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('number_documment', 'like', "%{$search}%")
                ->orWhereRaw("CONCAT(name,' ',last_name) LIKE ?", ["%{$search}%"])
                ->orWhereHas('areas', function ($q) use ($search) {
                    $q->where('name_area', 'like', "%{$search}%");
                });
            });
        }

        $users = $query
            ->orderBy('name')
            ->orderBy('last_name')
            ->paginate(15);

        $userRoles = $users->mapWithKeys(fn ($user) => [
            $user->id => $user->roles->pluck('name')->toArray()
        ]);

        return view('academic.userList', compact('users', 'userRoles'));
    }


    /* ======================================================
     |  GUARDAR USUARIO
     ====================================================== */
    public function store_user(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $role      = Role::findOrFail($request->role);
        $role_name = $role->name;

        $stateActivo = 1;
        $statePiar   = 2;

        $exist_user = Users_teacher::where('number_documment', $request->number_documment)->first();

        /* ==========================
         | DOCENTE
         ========================== */
        if ($role_name === 'docente') {

            $request->validate([
                'number_documment' => 'required|digits_between:1,11|unique:users_teachers,number_documment,' . optional($exist_user)->id,
                'name'             => 'required',
                'last_name'        => 'required',
                'email'            => 'required|unique:users_teachers,email,' . optional($exist_user)->id,
                'areas'            => 'required|array',
                'groups'           => 'required|array',
                'area_id'          => 'required|array',
                'groups_asig'      => 'required|array',
                'group_director'   => 'nullable|unique:users_teachers,group_director,' . optional($exist_user)->id,
            ]);

            // Validación cruzada grupos ↔ áreas
            foreach ($request->groups_asig as $areaId => $groups) {
                foreach ($groups as $groupId) {
                    if (!in_array($groupId, $request->groups)) {
                        return back()->with('error', 'Los grupos asignados a las áreas no coinciden con los grupos seleccionados.');
                    }

                    $exists = Teachers_areas_group::where('id_area', $areaId)
                        ->where('id_group', $groupId)
                        ->exists();

                    if ($exists) {
                        return back()->with('error', 'Ya existe un docente asignado a esa área y grupo.');
                    }
                }
            }

            DB::transaction(function () use ($request, $exist_user, $role_name, $stateActivo) {

                $user = $exist_user ?? new Users_teacher();

                $user->fill([
                    'number_documment' => $request->number_documment,
                    'name'             => $request->name,
                    'last_name'        => $request->last_name,
                    'email'            => $request->email,
                    'group_director'   => $request->group_director,
                    'id_state'         => $stateActivo,
                ]);

                if (!$exist_user) {
                    $user->password = bcrypt($request->number_documment);
                }

                $user->save();

                if (!$exist_user) {
                    $user->assignRole($role_name);
                }

                Users_load_area::where('id_user_teacher', $user->id)->delete();
                Users_load_group::where('id_user_teacher', $user->id)->delete();
                Teachers_areas_group::where('id_teacher', $user->id)->delete();

                foreach ($request->areas as $area) {
                    Users_load_area::create([
                        'id_user_teacher' => $user->id,
                        'id_area' => $area
                    ]);
                }

                foreach ($request->groups as $group) {
                    Users_load_group::create([
                        'id_user_teacher' => $user->id,
                        'id_group' => $group
                    ]);
                }

                foreach ($request->groups_asig as $areaId => $groups) {
                    foreach ($groups as $groupId) {
                        Teachers_areas_group::create([
                            'id_teacher' => $user->id,
                            'id_area'    => $areaId,
                            'id_group'   => $groupId,
                        ]);
                    }
                }
            });

            return back()->with('success', 'Docente guardado correctamente.');
        }

        /* ==========================
         | PSICOORIENTADOR
         ========================== */
        if ($role_name === 'psicoorientador') {

            $request->validate([
                'number_documment' => 'required|digits_between:1,11|unique:users_teachers,number_documment,' . optional($exist_user)->id,
                'name'             => 'required',
                'last_name'        => 'required',
                'email'            => 'required|unique:users_teachers,email,' . optional($exist_user)->id,
                'load_degree'      => 'required|array',
            ]);

            foreach ($request->load_degree as $degree) {
                if (Users_load_degree::where('id_degree', $degree)->exists()) {
                    return back()->with('error', 'Uno o más grados ya están asignados a otro psicoorientador.');
                }
            }

            DB::transaction(function () use ($request, $exist_user, $role_name, $stateActivo, $statePiar) {

                $user = $exist_user ?? new Users_teacher();

                $user->fill([
                    'number_documment' => $request->number_documment,
                    'name'             => $request->name,
                    'last_name'        => $request->last_name,
                    'email'            => $request->email,
                    'id_state'         => $exist_user ? $stateActivo : $statePiar,
                ]);

                if (!$exist_user) {
                    $user->password = bcrypt($request->number_documment);
                }

                $user->save();

                if (!$exist_user) {
                    $user->assignRole($role_name);
                }

                Users_load_degree::where('id_user', $user->id)->delete();

                foreach ($request->load_degree as $degree) {
                    Users_load_degree::create([
                        'id_user'   => $user->id,
                        'id_degree' => $degree,
                    ]);
                }
            });

            return back()->with('success', 'Psicoorientador guardado correctamente.');
        }

        return back()->with('error', 'Rol no válido.');
    }
}
