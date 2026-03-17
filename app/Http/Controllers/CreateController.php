<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Degree;
use App\Models\Group;
use App\Models\Users_teacher;
use App\Models\Teachers_areas_group;
use App\Http\Requests\Academic\StoreUserRequest;
use App\Http\Requests\Academic\UpdateUserRequest;
use App\Services\AcademicUserService;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function store_user(StoreUserRequest $request, AcademicUserService $service)
    {
        $data = $request->validated();

        $roleId = $data['role'];
        $roleName = Role::findOrFail($roleId)->name;

        if ($roleName === 'docente') {
            $service->storeDocente($data);
            return redirect()->back()->with('success', 'Usuario guardado correctamente.');
        }

        if ($roleName === 'psicoorientador') {
            $service->storePsicoorientador($data);
            return redirect()->back()->with('success', 'Usuario guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Rol no soportado para esta acción.');
    }

    // Listar usuarios (docentes y psicoorientadores).
    public function index_users(Request $request)
    {
        $query = Users_teacher::whereHas('roles', function ($q) {
            $q->whereIn('name', ['docente', 'psicoorientador']);
        });

        // 🔥 FILTRO POR ESTADO
        if ($request->filled('estado') && $request->estado != 'todos') {

            $query->whereHas('state', function ($q) use ($request) {
                $q->where('state', $request->estado);
            });
        }

        // 🔎 BÚSQUEDA
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

        $users = $query->with([
                    'groups',
                    'areas',
                    'roles',
                    'state',
                    'loadDegrees.degree'
                ])
                ->orderBy('name')
                ->orderBy('last_name')
                ->paginate(15)
                ->withQueryString(); // 🔥 IMPORTANTE

        $userRoles = $users->mapWithKeys(fn ($user) => [
            $user->id => $user->roles->pluck('name')->all()
        ]);

        return view('academic.userList', compact('users', 'userRoles'));
    }


    // Vista de editar usuario (docentes y psicoorientadores).
    public function edit_user(string $id)
    {

        $user = Users_teacher::with(['areas', 'groups', 'roles', 'loadDegrees'])->findOrFail($id);
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

        $tagGroups = Teachers_areas_group::where('id_teacher', $user->id)
            ->join('groups', 'teachers_areas_groups.id_group', '=', 'groups.id')
            ->select(
                'teachers_areas_groups.id_area as area_id',
                'groups.id',
                'groups.group'
            )
            ->get()
            ->groupBy('area_id');

        foreach ($selectedAreas as $area) {
            $groupsForArea[] = [
                'area_id' => $area['id'],
                'groups' => ($tagGroups[$area['id']] ?? collect())
                    ->map(fn ($row) => ['id' => $row->id, 'group' => $row->group])
                    ->values()
                    ->toArray(),
            ];
        }

        // Log::info('valor de ID Role: ' . json_encode($groupsForArea));
        // return response()->json(['Areas' => $groupsForArea]);

        return view('academic.userEdit', compact('user', 'roles', 'areas', 'selectedAreas', 'groups', 'selectedGroups', 'degrees', 'selectedDegrees', 'groupsForArea'));
    }

    // Editar usuarios (docentes y psicoorientadores)
    public function update_user(UpdateUserRequest $request, string $id, AcademicUserService $service)
    {
        $user = Users_teacher::findOrFail($id);
        $roleActual = $user->roles->first()?->name;
        $data = $request->validated();

        if ($roleActual === 'docente') {
            $service->updateDocente($user, $data);
            return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
        }

        if ($roleActual === 'psicoorientador') {
            $service->updatePsicoorientador($user, $data);
            return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
        }

        return redirect()->back()->with('error', 'Rol no soportado para esta acción.');

    }

    public function destroy_user($id)
    {
        $user = Users_teacher::findOrFail($id);

        // Si está activo → suspender
        if ($user->id_state == 1) {
            $user->id_state = 2;
            $message = 'Usuario suspendido correctamente';
        }
        // Si está suspendido → activar
        else {
            $user->id_state = 1;
            $message = 'Usuario activado correctamente';
        }

        $user->save();

        return redirect()->back()->with('success', $message);
    }

}