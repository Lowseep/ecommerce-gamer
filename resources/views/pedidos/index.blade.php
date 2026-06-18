@extends('layouts.app')

@section('titulo', 'Mis Pedidos')

@section('contenido')

<h2 class="text-xl md:text-2xl font-bold text-white mb-6">
    <i class="fas fa-box text-cyan-400 mr-2"></i> Mis Pedidos
</h2>

@if($pedidos->isEmpty())
    <div class="text-center py-16 md:py-20 bg-gray-900 border border-gray-800 rounded-2xl px-4">
        <i class="fas fa-box-open text-gray-600 text-5xl md:text-6xl mb-4"></i>
        <p class="text-gray-400 text-lg">Aún no tienes pedidos</p>
        <a href="{{ route('tienda.index') }}"
           class="mt-4 inline-block btn-gamer text-white px-6 py-3 rounded-lg text-sm font-medium">
            <i class="fas fa-store mr-2"></i> Ir a la tienda
        </a>
    </div>
@else
    <div class="space-y-4">
        @foreach($pedidos as $pedido)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">

                <!-- Info principal -->
                <div>
                    <div class="flex items-center gap-2 md:gap-3 mb-2 flex-wrap">
                        <span class="text-cyan-400 font-bold tracking-wide text-sm md:text-base">{{ $pedido->codigo }}</span>
                        @php
                            $colores = [
                                'pendiente'  => 'bg-yellow-900 text-yellow-300 border-yellow-700',
                                'procesando' => 'bg-blue-900 text-blue-300 border-blue-700',
                                'confirmado' => 'bg-cyan-900 text-cyan-300 border-cyan-700',
                                'enviado'    => 'bg-purple-900 text-purple-300 border-purple-700',
                                'entregado'  => 'bg-green-900 text-green-300 border-green-700',
                                'cancelado'  => 'bg-red-900 text-red-300 border-red-700',
                            ];
                            $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300 border-gray-700';
                        @endphp
                        <span class="text-xs px-2.5 md:px-3 py-1 rounded-full border {{ $color }} capitalize flex-shrink-0">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </div>
                    <p class="text-gray-400 text-xs md:text-sm">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $pedido->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-gray-400 text-xs md:text-sm mt-1">
                        <i class="fas fa-box mr-1"></i>
                        {{ $pedido->detalles->count() ?? '—' }} producto(s)
                    </p>
                </div>

                <!-- Total y acción -->
                <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 border-gray-800 pt-3 md:pt-0">
                    <div>
                        <p class="text-xs text-gray-500">Total</p>
                        <p class="text-cyan-400 font-bold text-lg">
                            S/ {{ number_format($pedido->total, 2) }}
                        </p>
                    </div>
                    <a href="{{ route('pedidos.detalle', $pedido->codigo) }}"
                       class="btn-gamer text-white px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap">
                        <i class="fas fa-eye mr-1"></i> Ver detalle
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-6 overflow-x-auto">
        {{ $pedidos->links() }}
    </div>
@endif

@endsection