<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\Producto;

class CarritoController extends Controller
{
    // Ver carrito
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver tu carrito.');
        }

        $carrito = Carrito::with('detalles.producto')
            ->where('usuario_id', Auth::id())
            ->first();

        $total = 0;
        if ($carrito) {
            $total = $carrito->detalles->sum(function ($detalle) {
                return $detalle->cantidad * $detalle->precio_unitario;
            });
        }

        return view('carrito.index', compact('carrito', 'total'));
    }

    // Agregar producto al carrito
    public function agregar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para agregar productos.');
        }

        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock disponible
        if ($producto->stock < $request->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        // Obtener o crear carrito del usuario
        $carrito = Carrito::firstOrCreate(['usuario_id' => Auth::id()]);

        // Verificar si el producto ya está en el carrito
        $detalle = DetalleCarrito::where('carrito_id', $carrito->id)
            ->where('producto_id', $producto->id)
            ->first();

        if ($detalle) {
            $nuevaCantidad = $detalle->cantidad + $request->cantidad;
            if ($nuevaCantidad > $producto->stock) {
                return back()->with('error', 'No hay suficiente stock para esa cantidad.');
            }
            $detalle->update(['cantidad' => $nuevaCantidad]);
        } else {
            DetalleCarrito::create([
                'carrito_id'      => $carrito->id,
                'producto_id'     => $producto->id,
                'cantidad'        => $request->cantidad,
                'precio_unitario' => $producto->precio,
            ]);
        }

        if (str_contains(url()->previous(), '/carrito')) {
            return back();
        }
        return back()->with('success', 'Añadido al carrito');
    }

    // Quitar producto del carrito
    public function quitar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'detalle_id' => 'required|exists:detalle_carrito,id',
        ]);

        $carrito = Carrito::where('usuario_id', Auth::id())->first();

        if ($carrito) {
            $detalle = DetalleCarrito::where('id', $request->detalle_id)
                ->where('carrito_id', $carrito->id)
                ->first();

            if ($detalle) {
                // Si acción es restar y cantidad > 1, solo restamos 1
                if ($request->accion === 'restar' && $detalle->cantidad > 1) {
                    $detalle->update(['cantidad' => $detalle->cantidad - 1]);
                } else {
                    // Eliminar completamente
                    $detalle->delete();
                }
            }
        }

        return back();
    }

    // Vaciar carrito completo
    public function vaciar(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $carrito = Carrito::where('usuario_id', Auth::id())->first();

        if ($carrito) {
            DetalleCarrito::where('carrito_id', $carrito->id)->delete();
        }

        return back()->with('success', 'Carrito vaciado.');
    }
}