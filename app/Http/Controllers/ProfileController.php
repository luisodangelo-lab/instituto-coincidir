<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
    'avatar' => [
        'required',
        'file',
        'max:2048', // 2MB
        'mimetypes:image/jpeg,image/pjpeg,image/png,image/webp',
    ],
    ]);


        $user = $request->user();

        // Borrar avatar anterior si existía
        if (!empty($user->avatar_path) && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $file = $request->file('avatar');
        $ext  = $file->getClientOriginalExtension() ?: 'jpg';
        $name = 'u' . $user->id . '_' . time() . '.' . $ext;

        // Guardar en storage/app/public/avatars
        $path = $file->storeAs('avatars', $name, 'public');

        $user->avatar_path = $path; // ej: avatars/u1_1700000000.webp
        $user->save();

        return back()->with('ok', 'Foto de perfil actualizada.');
    }

    public function removeAvatar(Request $request)
    {
        $user = $request->user();

        if (!empty($user->avatar_path) && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->avatar_path = null;
        $user->save();

        return back()->with('ok', 'Foto de perfil eliminada.');
    }
}
