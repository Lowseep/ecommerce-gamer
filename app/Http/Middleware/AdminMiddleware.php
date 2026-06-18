<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está autenticado, manda al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Si no es administrador, manda a la tienda
        if (Auth::user()->rol !== 'administrador') {
            return redirect()->route('tienda.index')
                ->with('error', 'No tienes permisos para acceder al panel.');
        }

        return $next($request);
    }
}