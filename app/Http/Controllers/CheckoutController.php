<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\SeguimientoPedido;
use App\Jobs\ProcesarPedidoJob;
use App\Services\PedidoService;

class CheckoutController extends Controller
{
    // Mostrar formulario de checkout
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para continuar.');
        }

        $carrito = Carrito::with('detalles.producto')
            ->where('usuario_id', Auth::id())
            ->first();

        if (!$carrito || $carrito->detalles->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $total = $carrito->detalles->sum(function ($detalle) {
            return $detalle->cantidad * $detalle->precio_unitario;
        });

        return view('checkout.index', compact('carrito', 'total'));
    }

    // Procesar el pedido
    public function procesar(Request $request, PedidoService $pedidoService)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'direccion' => 'required|string|max:500',
        ], [
            'direccion.required' => 'La dirección de envío es obligatoria.',
            'direccion.min'      => 'La dirección es muy corta.',
        ]);

        $carrito = Carrito::with('detalles.producto')
            ->where('usuario_id', Auth::id())
            ->first();

        if (!$carrito || $carrito->detalles->isEmpty()) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        // CONCEPTO SO: Mutex por PRODUCTO (no por usuario) para evitar
        // condición de carrera cuando dos usuarios distintos compran
        // el mismo producto al mismo tiempo.
        //
        // Se ordenan los IDs de producto para evitar deadlocks: si dos
        // pedidos comparten productos, ambos intentan adquirir los locks
        // siempre en el mismo orden (evita que A espere por B mientras
        // B espera por A).
        $productosIds = $carrito->detalles->pluck('producto_id')->sort()->values();

        $locks = [];

        try {
            // Adquirir un lock por cada producto del carrito
            foreach ($productosIds as $productoId) {
                $lockKey = "stock_producto_{$productoId}";
                $lock = Cache::lock($lockKey, 10);

                if (!$lock->get()) {
                    // No se pudo bloquear: otro usuario está comprando
                    // ese mismo producto justo ahora. Liberar lo que ya
                    // se haya bloqueado y abortar.
                    foreach ($locks as $l) {
                        $l->release();
                    }
                    return back()->with('error', 'Alguien más está comprando uno de tus productos en este momento. Intenta nuevamente en unos segundos.');
                }

                $locks[] = $lock;
            }

            // A partir de aquí tenemos el lock exclusivo de TODOS los
            // productos del carrito. Ningún otro proceso puede tocar su
            // stock hasta que liberemos los locks.

            $total = $carrito->detalles->sum(function ($detalle) {
                return $detalle->cantidad * $detalle->precio_unitario;
            });

            // Verificar stock real (releído de BD, no de memoria/caché)
            foreach ($carrito->detalles as $detalle) {
                $detalle->producto->refresh();
                if ($detalle->producto->stock < $detalle->cantidad) {
                    return back()->with('error', 'El producto "' . $detalle->producto->nombre . '" ya no tiene stock suficiente.');
                }
            }

            // Generar código único del pedido
            $codigo = $pedidoService->generarCodigo();

            // Todo dentro de una transacción de BD: si algo falla,
            // se revierte todo (pedido, detalles, stock).
            $pedido = DB::transaction(function () use ($carrito, $codigo, $total, $request) {

                $pedido = Pedido::create([
                    'usuario_id'      => Auth::id(),
                    'codigo'          => $codigo,
                    'estado'          => 'pendiente',
                    'total'           => $total,
                    'direccion_envio' => $request->direccion,
                ]);

                foreach ($carrito->detalles as $detalle) {
                    DetallePedido::create([
                        'pedido_id'       => $pedido->id,
                        'producto_id'     => $detalle->producto_id,
                        'cantidad'        => $detalle->cantidad,
                        'precio_unitario' => $detalle->precio_unitario,
                    ]);

                    // Descontar stock de forma segura (estamos dentro del lock)
                    $detalle->producto->decrement('stock', $detalle->cantidad);
                }

                SeguimientoPedido::create([
                    'pedido_id'   => $pedido->id,
                    'estado'      => 'pendiente',
                    'descripcion' => 'Pedido recibido y en espera de confirmación.',
                ]);

                DetalleCarrito::where('carrito_id', $carrito->id)->delete();

                return $pedido;
            });

            // CONCEPTO SO: Enviar a cola de procesamiento en background
            ProcesarPedidoJob::dispatch($pedido->id);

        } finally {
            // Liberar todos los locks adquiridos, sin importar si hubo
            // error o no (equivalente a release() de un semáforo).
            foreach ($locks as $lock) {
                $lock->release();
            }
        }

        return redirect()->route('checkout.confirmacion', $codigo);
    }

    // Página de confirmación
    public function confirmacion($codigo)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pedido = Pedido::with('detalles.producto')
            ->where('codigo', $codigo)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        return view('checkout.confirmacion', compact('pedido'));
    }
}