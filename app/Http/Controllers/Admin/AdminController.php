<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;

class AdminController extends Controller
{
    public function index()
    {
        // métricas del dashboard
        $totalPedidos   = Pedido::count();
        $totalProductos = Producto::count();
        $totalClientes  = Usuario::where('rol', 'cliente')->count();
        $totalIngresos  = Pedido::whereNotIn('estado', ['cancelado'])->sum('total');
        $productosSinStock = Producto::where('stock', 0)->count();
        $totalUsuarios = Usuario::count();
        $pedidosRecientes = Pedido::latest()->take(5)->get();

        return response()->view('admin.dashboard', compact('productosSinStock', 'totalUsuarios', 'pedidosRecientes',
            'totalPedidos', 'totalProductos', 'totalClientes', 'totalIngresos'
        ))->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}