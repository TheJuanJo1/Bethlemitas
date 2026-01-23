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

        // Roles
        $roles = $user->roles->pluck('name')->toArray();

        // Grados asignados
        $degrees = Users_load_degree::where('id_user', $user->id)
            ->with('degree')
            ->get()
            ->pluck('degree.degree');

        // Director de grupo
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

    // ✉️ ACTUALIZAR CORREO
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

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ]);
        }

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Correo actualizado correctamente.');
    }

    // 📸 SUBIR / REEMPLAZAR FOTO DE PERFIL
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $folder = public_path('Imagenes_Perfil');

        // Crear carpeta si no existe
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        // Extensión del archivo
        $extension = $request->file('photo')->getClientOriginalExtension();

        // Nombre fijo por usuario
        $fileName = 'perfil_' . $user->id . '.' . $extension;

        // Eliminar cualquier foto anterior del usuario
        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $oldFile = $folder . '/perfil_' . $user->id . '.' . $ext;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Guardar nueva foto
        $request->file('photo')->move($folder, $fileName);

        return back()->with('success_photo', 'Foto de perfil actualizada correctamente.');
    }

    // 🗑️ ELIMINAR FOTO DE PERFIL
    public function deletePhoto()
    {
        $user = Auth::user();
        $folder = public_path('Imagenes_Perfil');

        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $file = $folder . '/perfil_' . $user->id . '.' . $ext;
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return back()->with('success_photo', 'Foto de perfil eliminada correctamente.');
    }
}
