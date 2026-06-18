<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable as BusQueueable;
use App\Models\Pedido;
use App\Models\SeguimientoPedido;
use Illuminate\Support\Facades\Log;

class ProcesarPedidoJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(public int $pedidoId) {}

    // CONCEPTO SO: Este método corre en un proceso separado (worker)
    // gestionado por Supervisor, demostrando procesos en background
    public function handle(): void
    {
        $pedido = Pedido::find($this->pedidoId);

        if (!$pedido) {
            Log::error("ProcesarPedidoJob: Pedido {$this->pedidoId} no encontrado.");
            return;
        }

        // Simular procesamiento en background (concepto SO)
        sleep(2);

        // Cambiar estado a procesando (el admin luego lo confirma manualmente)
        $pedido->update(['estado' => 'procesando']);

        // Registrar en seguimiento
        SeguimientoPedido::create([
            'pedido_id'   => $pedido->id,
            'estado'      => 'procesando',
            'descripcion' => 'Pedido recibido por el sistema y en proceso de verificación.',
        ]);

        Log::info("Pedido {$pedido->codigo} procesado correctamente por el worker.");
    }
}