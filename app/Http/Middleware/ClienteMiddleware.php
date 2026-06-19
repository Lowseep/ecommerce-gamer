<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class ClienteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::user()->rol === 'administrador') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Los administradores no pueden acceder a esta sección.');
        }
        return $next($request);
    }
}
