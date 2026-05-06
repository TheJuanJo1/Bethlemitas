<?php

namespace App\Http\Controllers;

use App\Http\Requests\Academic\StoreUserRequest;
use App\Http\Requests\Academic\UpdateUserRequest;
use App\Models\Area;
use App\Models\Degree;
use App\Models\Group;
use App\Models\State;
use App\Models\Users_teacher;
use App\Services\AcademicUserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CreateController extends Controller
{
    public function index_users(Request $request)
    {
        // 1. NO mostrar al Coordinador en la lista
        $query = Users_teacher::role(['docente', 'psicoorientador'])
            ->with(['roles', 'areas', 'groups', 'loadDegrees.degree', 'director']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('last_name', 'LIKE', "%$search%")
                    ->orWhere('number_documment', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('estado') && $request->estado !== 'todos') {
            if ($request->estado === 'activo') {
                $query->where('id_state', 1);
            }
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);

        $userRoles = [];
        foreach ($users as $user) {
            $userRoles[$user->id] = $user->getRoleNames()->toArray();
        }

        if ($request->ajax()) {
            return view('academic.userList', compact('users', 'userRoles'))->render();
        }

        return view('academic.userList', compact('users', 'userRoles'));
    }

    public function create_user()
    {
        $areas = Area::orderBy('name_area', 'asc')->get();
        $groups = Group::orderBy('group', 'asc')->get();
        $degrees = Degree::orderBy('degree', 'asc')->get();
        
        // 2. NO permitir crear otros Coordinadores
        $roles = Role::whereIn('name', ['docente', 'psicoorientador'])->get();

        return view('academic.createUser', compact('areas', 'groups', 'roles', 'degrees'));
    }

    public function store_user(StoreUserRequest $request, AcademicUserService $service)
    {
        $data = $request->validated();
        $role = Role::findOrFail($data['role']);

        try {
            if ($role->name === 'docente') {
                $service->storeDocente($data);
            } elseif ($role->name === 'psicoorientador') {
                $service->storePsicoorientador($data);
            }
            return redirect()->route('index.users')->with('success', 'Usuario creado correctamente.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit_user(string $id)
    {
        $user = Users_teacher::with(['roles', 'areas', 'groups', 'loadDegrees'])->findOrFail($id);
        
        // Si por alguna razón se intenta editar a un coordinador desde URL, bloquear
        if ($user->hasRole('coordinador')) {
            return redirect()->route('index.users')->with('error', 'No se puede editar al administrador principal.');
        }

        $areas = Area::orderBy('name_area', 'asc')->get();
        $groups = Group::orderBy('group', 'asc')->get();
        $degrees = Degree::orderBy('degree', 'asc')->get();
        
        // 2. NO mostrar rol coordinador en edición
        $roles = Role::whereIn('name', ['docente', 'psicoorientador'])->get();

        $selectedAreas = $user->areas->toArray();
        $selectedGroups = $user->groups->pluck('id')->toArray();
        $selectedDegrees = $user->loadDegrees->pluck('id_degree')->toArray();

        $groupsForArea = [];
        $tags = \DB::table('teachers_areas_groups')->where('id_teacher', $id)->get()->groupBy('id_area');
        foreach ($tags as $areaId => $assignments) {
            $groupsForArea[] = [
                'area_id' => $areaId,
                'groups' => $assignments->map(fn($tag) => ['id' => $tag->id_group])->toArray()
            ];
        }

        return view('academic.userEdit', compact(
            'user', 'areas', 'groups', 'roles', 'selectedAreas', 
            'selectedGroups', 'degrees', 'selectedDegrees', 'groupsForArea'
        ));
    }

    public function update_user(UpdateUserRequest $request, string $id, AcademicUserService $service)
    {
        $user = Users_teacher::findOrFail($id);
        $data = $request->validated();

        try {
            $service->updateUser($user, $data);
            return redirect()->route('index.users')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy_user(string $id)
    {
        $user = Users_teacher::findOrFail($id);
        $newState = ($user->id_state == 1) ? 2 : 1;
        $user->id_state = $newState;
        $user->save();

        return back()->with('success', $newState == 1 ? 'Usuario activado.' : 'Usuario bloqueado.');
    }
}
