<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Roles
        $roles = $user->roles->pluck('name')->toArray();

        // Grados asignados
        $degrees = Users_load_degree::where('id_user', $user->id)
            ->with('degree')
            ->get()
            ->pluck('degree.degree');

        // Director de grupo (SOLO usa id)
        $directorGroup = Users_teacher::where('id', $user->id)
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
