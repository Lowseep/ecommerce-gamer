<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pedido;

class MisPedidosController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver tus pedidos.');
        }

        // Admin no puede ver mis pedidos
        if (Auth::user()->rol === 'administrador') {
            return redirect()->route('admin.dashboard');
        }

        $pedidos = Pedido::where('usuario_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function detalle($codigo)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin no puede ver mis pedidos
        if (Auth::user()->rol === 'administrador') {
            return redirect()->route('admin.dashboard');
        }

        $pedido = Pedido::with(['detalles.producto', 'seguimientos'])
            ->where('codigo', $codigo)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        return view('pedidos.detalle', compact('pedido'));
    }
}