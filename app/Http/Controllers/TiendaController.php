<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use App\Models\Categoria;

class TiendaController extends Controller
{
    // Página principal — catálogo de productos
    public function index(Request $request)
    {
        // Si es admin, redirigir al panel
        if (Auth::check() && Auth::user()->rol === 'administrador') {
            return redirect()->route('admin.dashboard');
        }

        $categorias = Categoria::all();

        $query = Producto::with('categoria')->where('stock', '>', 0);

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->whereHas('categoria', function ($q) use ($request) {
                $q->where('slug', $request->categoria);
            });
        }

        // Filtro por búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        // Filtro por precio
        if ($request->has('orden')) {
            if ($request->orden === 'precio_asc') {
                $query->orderBy('precio', 'asc');
            } elseif ($request->orden === 'precio_desc') {
                $query->orderBy('precio', 'desc');
            }
        } else {
            $query->latest();
        }

        $productos = $query->paginate(10);

        return view('tienda.index', compact('productos', 'categorias'));
    }

    // Detalle de un producto
    public function show($slug)
    {
        $producto = Producto::with('categoria')
            ->where('slug', $slug)
            ->firstOrFail();

        // Productos relacionados de la misma categoría
        $relacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('tienda.producto', compact('producto', 'relacionados'));
    }

    // Filtrar por categoría
    public function categoria($slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();
        $categorias = Categoria::all();

        $productos = Producto::with('categoria')
            ->where('categoria_id', $categoria->id)
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        return view('tienda.index', compact('productos', 'categorias', 'categoria'));
    }
}