<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\SeguimientoPedido;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('usuario')
            ->latest()
            ->paginate(5);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function detalle($codigo)
    {
        $pedido = Pedido::with(['usuario', 'detalles.producto', 'seguimientos'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        return view('admin.pedidos.detalle', compact('pedido'));
    }

    public function cambiarEstado(Request $request, $codigo)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,procesando,confirmado,enviado,entregado,cancelado',
        ]);

        $pedido      = Pedido::with('detalles.producto')->where('codigo', $codigo)->firstOrFail();
        $estadoNuevo = $request->estado;
        $estadoActual = $pedido->estado;

        // ── Restricciones ─────────────────────────────────────────
        // No modificar pedidos ya entregados
        if ($estadoActual === 'entregado') {
            return back()->with('error', 'No se puede modificar un pedido que ya fue entregado al cliente.');
        }

        // No modificar pedidos ya cancelados
        if ($estadoActual === 'cancelado') {
            return back()->with('error', 'Este pedido ya fue cancelado y no puede modificarse.');
        }

        // No seleccionar el mismo estado
        if ($estadoNuevo === $estadoActual) {
            return back()->with('error', 'El pedido ya se encuentra en ese estado.');
        }

        $orden = ['pendiente', 'procesando', 'confirmado', 'enviado', 'entregado'];

        $descripciones = [
            'pendiente'  => 'Pedido recibido y en espera de confirmación.',
            'procesando' => 'Pedido en proceso de verificación por el sistema.',
            'confirmado' => 'Pedido confirmado y preparándose para envío.',
            'enviado'    => 'Pedido enviado al destino indicado.',
            'entregado'  => 'Pedido entregado correctamente al cliente.',
            'cancelado'  => 'Pedido cancelado por el administrador.',
        ];

        // ── Cancelar pedido ───────────────────────────────────────
        if ($estadoNuevo === 'cancelado') {
            // Solo se puede cancelar si no fue entregado (ya validado arriba)
            foreach ($pedido->detalles as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }

            $pedido->update(['estado' => 'cancelado']);

            SeguimientoPedido::create([
                'pedido_id'   => $pedido->id,
                'estado'      => 'cancelado',
                'descripcion' => $descripciones['cancelado'],
            ]);

            return back()->with('success', 'Pedido cancelado y stock restaurado correctamente.');
        }

        // ── Avanzar estado ────────────────────────────────────────
        $indexActual = array_search($estadoActual, $orden);
        $indexNuevo  = array_search($estadoNuevo, $orden);

        // No retroceder estados
        if ($indexNuevo === false || $indexNuevo <= $indexActual) {
            return back()->with('error', 'No puedes retroceder el estado del pedido.');
        }

        // Crear seguimiento para cada estado intermedio que falte
        $estadosRegistrados = SeguimientoPedido::where('pedido_id', $pedido->id)
            ->pluck('estado')
            ->toArray();

        for ($i = $indexActual + 1; $i <= $indexNuevo; $i++) {
            $estado = $orden[$i];
            if (!in_array($estado, $estadosRegistrados)) {
                SeguimientoPedido::create([
                    'pedido_id'   => $pedido->id,
                    'estado'      => $estado,
                    'descripcion' => $descripciones[$estado],
                ]);
            }
        }

        $pedido->update(['estado' => $estadoNuevo]);

        return back()->with('success', 'Estado actualizado a "' . ucfirst($estadoNuevo) . '" correctamente.');
    }
}