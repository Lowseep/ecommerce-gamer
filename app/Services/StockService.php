<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Facades\Cache;

class StockService
{
    // CONCEPTO SO: Mutex distribuido con Redis para sincronización
    // Evita condición de carrera cuando dos usuarios compran el mismo producto
    public function verificarYReservar(int $productoId, int $cantidad): bool
    {
        $lockKey = "stock_producto_{$productoId}";

        // Intentar obtener el lock por 5 segundos
        $lock = Cache::lock($lockKey, 5);

        if (!$lock->get()) {
            return false;
        }

        try {
            $producto = Producto::find($productoId);

            if (!$producto || $producto->stock < $cantidad) {
                return false;
            }

            $producto->decrement('stock', $cantidad);
            return true;

        } finally {
            // Siempre liberar el lock (equivalente a release() de semáforo)
            $lock->release();
        }
    }

    // Devolver stock si se cancela el pedido
    public function devolverStock(int $productoId, int $cantidad): void
    {
        $lockKey = "stock_producto_{$productoId}";
        $lock    = Cache::lock($lockKey, 5);

        if ($lock->get()) {
            try {
                $producto = Producto::find($productoId);
                if ($producto) {
                    $producto->increment('stock', $cantidad);
                }
            } finally {
                $lock->release();
            }
        }
    }
}