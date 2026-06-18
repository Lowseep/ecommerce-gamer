@extends('layouts.app')

@section('titulo', 'Pedido Confirmado')

@section('contenido')

<div class="max-w-2xl mx-auto">

    <!-- Éxito -->
    <div class="text-center py-6 md:py-10">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-white text-2xl md:text-3xl"></i>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-white">¡Pedido realizado!</h1>
        <p class="text-gray-400 mt-2 text-sm md:text-base px-4">Tu pedido ha sido recibido y está siendo procesado.</p>
    </div>

    <!-- Código del pedido -->
    <div class="bg-gray-900 border border-cyan-500 border-opacity-50 rounded-2xl p-5 md:p-6 text-center mb-6">
        <p class="text-gray-400 text-sm mb-2">Código de tu pedido</p>
        <p class="text-2xl md:text-3xl font-bold text-cyan-400 tracking-wide md:tracking-widest break-all">{{ $pedido->codigo }}</p>
        <p class="text-gray-500 text-xs mt-2">Guarda este código para hacer seguimiento</p>
    </div>

    <!-- Detalle -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6 mb-6">
        <h3 class="text-white font-semibold mb-4">
            <i class="fas fa-box text-cyan-400 mr-2"></i> Detalle del pedido
        </h3>

        <div class="space-y-3">
            @foreach($pedido->detalles as $detalle)
                <div class="flex justify-between items-center text-sm gap-3">
                    <div class="min-w-0">
                        <p class="text-white truncate">{{ $detalle->producto->nombre }}</p>
                        <p class="text-gray-400 text-xs">x{{ $detalle->cantidad }}</p>
                    </div>
                    <span class="text-cyan-400 font-medium flex-shrink-0">
                        S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                    </span>
                </div>
            @endforeach
        </div>

        <hr class="border-gray-700 my-4">

        <div class="flex justify-between text-white font-bold">
            <span>Total pagado</span>
            <span class="text-cyan-400">S/ {{ number_format($pedido->total, 2) }}</span>
        </div>

        <div class="mt-4 text-sm text-gray-400">
            <p class="flex items-start gap-2">
                <i class="fas fa-map-marker-alt text-cyan-400 mt-0.5"></i>
                <span>{{ $pedido->direccion_envio }}</span>
            </p>
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
        <a href="{{ route('pedidos.detalle', $pedido->codigo) }}"
           class="flex-1 text-center btn-gamer text-white font-semibold py-3 rounded-lg">
            <i class="fas fa-search mr-2"></i> Ver mi pedido
        </a>
        <a href="{{ route('tienda.index') }}"
           class="flex-1 text-center border border-gray-700 text-gray-400 hover:text-white py-3 rounded-lg transition">
            <i class="fas fa-store mr-2"></i> Seguir comprando
        </a>
    </div>
</div>

@endsection