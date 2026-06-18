<?php

namespace App\Services;

use App\Models\Carrito;
use App\Models\DetalleCarrito;
use Illuminate\Support\Facades\Auth;

class CarritoService
{
    // Obtener carrito del usuario con sus detalles
    public function obtener()
    {
        if (!Auth::check()) return null;

        return Carrito::with('detalles.producto')
            ->where('usuario_id', Auth::id())
            ->first();
    }

    // Contar items en el carrito (para el navbar)
    public function contarItems(): int
    {
        if (!Auth::check()) return 0;

        $carrito = Carrito::where('usuario_id', Auth::id())->first();

        if (!$carrito) return 0;

        return DetalleCarrito::where('carrito_id', $carrito->id)->count();
    }
    
    // Calcular total del carrito
    public function calcularTotal(): float
    {
        $carrito = $this->obtener();

        if (!$carrito) return 0;

        return $carrito->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });
    }
}