<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Roles del usuario
        $roles = $user->roles->pluck('name')->toArray();

        // Grados asignados
        $degrees = Users_load_degree::where('id_user', $user->id)
            ->with('degree')
            ->get()
            ->pluck('degree.degree');

        // Director de grupo (RELACIÓN CORRECTA: group)
        $directorGroup = Users_teacher::where('id', $user->id)
            ->whereNotNull('group_director')
            ->with('group') // 🔥 AQUÍ ESTABA EL ERROR
            ->first();

        return view('profile.index', compact(
            'user',
            'roles',
            'degrees',
            'directorGroup'
        ));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users_teachers,email,' . Auth::id(),
            ],
            'password' => ['required'],
        ]);

        $user = Auth::user();

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ]);
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Correo actualizado correctamente.');
    }
}
