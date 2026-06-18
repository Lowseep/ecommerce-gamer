<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->rol === 'administrador') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('tienda.index');
        }

        return response()->view('auth.login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    // Procesar login
    public function autenticar(Request $request)
    {
        $request->validate([
            'correo'     => [
                'required',
                'email',
            ],
            'contrasena' => [
                'required',
                'min:6',
            ],
        ], [
            'correo.required'     => 'El correo es obligatorio.',
            'correo.email'        => 'Ingresa un correo electrónico válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $credenciales = [
            'correo'   => strtolower(trim($request->correo)),
            'password' => $request->contrasena,
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return $this->redirigirSegunRol();
        }

        return back()->withErrors([
            'correo' => 'El correo o la contraseña son incorrectos. Verifica tus datos.',
        ])->withInput($request->only('correo'));
    }
    // Cerrar sesión
    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // Redirigir según rol
    private function redirigirSegunRol()
    {
        if (Auth::user()->rol === 'administrador') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tienda.index');
    }
}