<?php

namespace App\Http\Controllers;

use App\Models\Asignature;
use App\Models\Degree;
use App\Models\Group;
use App\Models\Users_teacher;
use Spatie\Permission\Models\Role;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Hace pruebas para verificar algun dato

class CreateController extends Controller
{
    // Vista create user
    public function create_user() {

        $groups = Group::all();
        $asignatures = Asignature::all();
        $degrees = Degree::all();
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
                'group_director' => 'nullable',
            ]);

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
        $asignatures = Asignature::all();  // Todas las asignaturas disponibles
        $selectedAsignatures = $user->asignatures->pluck('id')->toArray();
        $groups = Group::all();
        $selectedGroups = $user->groups->pluck('id')->toArray();
        

        
        

        return view('academic.userEdit', compact('user', 'roles', 'asignatures', 'selectedAsignatures', 'groups', 'selectedGroups'));
    }

}
