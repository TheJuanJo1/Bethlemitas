<?php

namespace App\Http\Controllers;

use App\Models\Asignature;
use App\Models\Degree;
use App\Models\Group;
use Spatie\Permission\Models\Role;
use Auth;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    // Vista create user
    public function create_user() {

        $groups = Group::all()->pluck('group');
        $asignatures = Asignature::all()->pluck('name_asignature');
        $degrees = Degree::all()->pluck('degree');
        $roles = Role::where('name', '!=', 'estudiante')
                       ->where('name', '!=', 'coordinador')
                       ->pluck('name');
        

        return view('academic.createUser', compact('groups', 'asignatures', 'degrees', 'roles'));
    }

    // Store user
    public function store_user(Request $request) {
        $request->validate([
            'role' => 'required|array|min:1',
            'role.*' => 'exists:roles,id',
        ]);

        $role = $request->role;
        
        if ($role == 'docente') {
            return 'hola sebas';
        }
    }
}
