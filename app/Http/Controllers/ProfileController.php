<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Users_load_degree;
use App\Models\Users_teacher;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Roles del usuario
        $roles = $user->roles->pluck('name')->toArray();

        // Grados asignados (psicoorientador)
        $degrees = Users_load_degree::where('id_user', $user->id)
            ->with('degree')
            ->get()
            ->pluck('degree.degree');

        // Grupo donde es director (si aplica)
        $directorGroup = Users_teacher::where('id', $user->id)
            ->whereNotNull('group_director')
            ->with('director') // ✅ RELACIÓN CORRECTA
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

    // 📸 SUBIR / REEMPLAZAR FOTO
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $documento = $user->number_documment;

        $folder = public_path('Imagenes_Perfil');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $extension = $request->file('photo')->getClientOriginalExtension();
        $fileName = 'perfil_' . $documento . '.' . $extension;

        // Eliminar fotos anteriores
        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $oldFile = $folder . '/perfil_' . $documento . '.' . $ext;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $request->file('photo')->move($folder, $fileName);

        return back()->with('success_photo', 'Foto de perfil actualizada correctamente.');
    }

    // 🗑️ ELIMINAR FOTO
    public function deletePhoto()
    {
        $user = Auth::user();
        $documento = $user->number_documment;
        $folder = public_path('Imagenes_Perfil');

        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $file = $folder . '/perfil_' . $documento . '.' . $ext;
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return back()->with('success_photo', 'Foto de perfil eliminada correctamente.');
    }

    // 📜 SUBIR / REEMPLAZAR FIRMA
    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $documento = $user->number_documment;

        $folder = public_path('Imagenes_Firma');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        // Eliminar firmas anteriores
        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $oldFile = $folder . '/firma_' . $documento . '.' . $ext;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $extension = $request->file('signature')->getClientOriginalExtension();
        $fileName = 'firma_' . $documento . '.' . $extension;
        $request->file('signature')->move($folder, $fileName);

        // Guardar ruta en la base de datos
        $user->signature = "Imagenes_Firma/" . $fileName;
        $user->save();

        return back()->with('success_signature', 'Firma actualizada correctamente.');
    }

    // 🗑️ ELIMINAR FIRMA
    public function deleteSignature()
    {
        $user = Auth::user();
        $documento = $user->number_documment;
        $folder = public_path('Imagenes_Firma');

        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            $file = $folder . '/firma_' . $documento . '.' . $ext;
            if (file_exists($file)) {
                unlink($file);
            }
        }

        // Borrar referencia en BD
        $user->signature = null;
        $user->save();

        return back()->with('success_signature', 'Firma eliminada correctamente.');
    }
}