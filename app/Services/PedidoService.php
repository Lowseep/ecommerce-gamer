<?php

namespace App\Services;

class PedidoService
{
    // Generar código único para el pedido
    public function generarCodigo(): string
    {
        return 'FS-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
    }

    // Obtener etiqueta de estado con color para las vistas
    public function etiquetaEstado(string $estado): array
    {
        return match ($estado) {
            'pendiente'  => ['texto' => 'Pendiente',  'color' => 'yellow'],
            'procesando' => ['texto' => 'Procesando', 'color' => 'blue'],
            'confirmado' => ['texto' => 'Confirmado', 'color' => 'cyan'],
            'enviado'    => ['texto' => 'Enviado',    'color' => 'purple'],
            'entregado'  => ['texto' => 'Entregado',  'color' => 'green'],
            'cancelado'  => ['texto' => 'Cancelado',  'color' => 'red'],
            default      => ['texto' => 'Desconocido','color' => 'gray'],
        };
    }
}