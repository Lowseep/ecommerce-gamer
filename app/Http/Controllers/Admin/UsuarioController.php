<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    // Lista de usuarios
    public function index()
    {
        $usuarios = Usuario::latest()->paginate(5);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Cambiar rol de un usuario
    public function cambiarRol(Request $request, $id)
    {
        $request->validate([
            'rol' => 'required|in:cliente,administrador',
        ]);

        $usuario = Usuario::findOrFail($id);

        // Evitar que el admin se quite el rol a sí mismo
        if (auth()->id() === $usuario->id && $request->rol !== 'administrador') {
            return back()->with('error', 'No puedes quitarte el rol de administrador a ti mismo.');
        }

        $usuario->update(['rol' => $request->rol]);

        return back()->with('success', 'Rol actualizado correctamente.');
    }
}