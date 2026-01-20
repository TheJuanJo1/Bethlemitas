<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Users_load_degree;
use App\Models\Degree;
use App\Models\Group;
use App\Models\Users_teacher;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Roles
        $roles = $user->roles->pluck('name')->toArray();

        // Grados asignados (si aplica)
        $degrees = Users_load_degree::where('id_user', $user->id)
            ->with('degree')
            ->get()
            ->pluck('degree.degree');

        // Si es docente, verificar si es director de grupo
        $directorGroup = Users_teacher::where('id_user', $user->id)
            ->whereNotNull('group_director')
            ->with('group')
            ->first();

        return view('profile.index', compact(
            'user',
            'roles',
            'degrees',
            'directorGroup'
        ));
    }
}
