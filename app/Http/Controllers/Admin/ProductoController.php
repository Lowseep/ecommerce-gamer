<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->latest()->paginate(5);
        return view('admin.productos.index', compact('productos'));
    }

    public function crear()
    {
        $categorias = Categoria::all();
        return view('admin.productos.crear', compact('categorias'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|min:3|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'required|string|min:10|max:5000',
            'precio'       => 'required|numeric|min:0.01',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'descripcion.required'  => 'La descripción es obligatoria.',
            'precio.required'       => 'El precio es obligatorio.',
            'stock.required'        => 'El stock es obligatorio.',
            'imagen.image'          => 'El archivo debe ser una imagen.',
        ]);

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create([
            'nombre'        => $request->nombre,
            'slug'          => Str::slug($request->nombre),
            'categoria_id'  => $request->categoria_id,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'precio_oferta' => $request->precio_oferta,
            'stock'         => $request->stock,
            'imagen'        => $imagenPath,
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function editar($id)
    {
        $producto   = Producto::findOrFail($id);
        $categorias = Categoria::all();
        return view('admin.productos.editar', compact('producto', 'categorias'));
    }

    public function actualizar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'required|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagenPath = $producto->imagen;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update([
            'nombre'        => $request->nombre,
            'slug'          => Str::slug($request->nombre),
            'categoria_id'  => $request->categoria_id,
            'descripcion'   => $request->descripcion,
            'precio'        => $request->precio,
            'stock'         => $request->stock,
            'imagen'        => $imagenPath,
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $producto = Producto::findOrFail($id);

        // Verificar si tiene pedidos activos (pendiente, procesando, confirmado, enviado)
        $pedidosActivos = $producto->detallesPedido()
            ->whereHas('pedido', function ($q) {
                $q->whereNotIn('estado', ['entregado', 'cancelado']);
            })->count();

        if ($pedidosActivos > 0) {
            return redirect()->route('admin.productos.index')
                ->with('error', 'No se puede eliminar "' . $producto->nombre . '" porque tiene pedidos activos asociados.');
        }

        // Eliminar detalles de pedidos entregados/cancelados antes de borrar el producto
        $producto->detallesPedido()->delete();

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto "' . $producto->nombre . '" eliminado correctamente.');
    }
}
