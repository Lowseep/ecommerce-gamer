<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class RegistroController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->rol === 'administrador') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('tienda.index');
        }

        return response()->view('auth.registro')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    // Procesar registro
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre'     => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s]+$/u', // Solo letras y espacios, sin números
            ],
            'correo'     => [
                'required',
                'email:rfc,dns',       // Valida formato Y que el dominio exista
                'max:255',
                'unique:usuarios,correo',
            ],
            'contrasena' => [
                'required',
                'min:6',
                'max:50',
                'confirmed',
            ],
        ], [
            'nombre.required'      => 'El nombre es obligatorio.',
            'nombre.min'           => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max'           => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex'         => 'El nombre solo puede contener letras y espacios, sin números ni símbolos.',
            'correo.required'      => 'El correo es obligatorio.',
            'correo.email'         => 'Ingresa un correo electrónico válido (ej: usuario@gmail.com).',
            'correo.unique'        => 'Este correo ya está registrado. Prueba con otro.',
            'correo.max'           => 'El correo no puede superar los 255 caracteres.',
            'contrasena.required'  => 'La contraseña es obligatoria.',
            'contrasena.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'contrasena.max'       => 'La contraseña no puede superar los 50 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden. Verifícalas.',
        ]);

        $usuario = Usuario::create([
            'nombre'     => trim($request->nombre),
            'correo'     => strtolower(trim($request->correo)),
            'contrasena' => $request->contrasena,
            'rol'        => 'cliente',
        ]);

        Auth::login($usuario);

        return redirect()->route('tienda.index')
            ->with('success', '¡Bienvenido ' . $usuario->nombre . '! Tu cuenta fue creada correctamente.');
    }
}