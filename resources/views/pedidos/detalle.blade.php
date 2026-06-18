@extends('layouts.app')

@section('titulo', 'Pedido ' . $pedido->codigo)

@section('contenido')

<!-- Breadcrumb -->
<nav class="text-sm text-gray-400 mb-6">
    <a href="{{ route('pedidos.index') }}" class="hover:text-cyan-400 transition">Mis Pedidos</a>
    <span class="mx-2">/</span>
    <span class="text-white break-all">{{ $pedido->codigo }}</span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Detalle productos -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Estado actual -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6">
            <div class="flex items-center justify-between mb-4 gap-2 flex-wrap">
                <h2 class="text-white font-bold text-base md:text-lg break-all">
                    <i class="fas fa-shopping-bag text-cyan-400 mr-2"></i>
                    {{ $pedido->codigo }}
                </h2>
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
                <span class="text-xs md:text-sm px-3 md:px-4 py-1 rounded-full border {{ $color }} capitalize flex-shrink-0">
                    {{ ucfirst($pedido->estado) }}
                </span>
            </div>

            <!-- Productos -->
            <div class="space-y-4">
                @foreach($pedido->detalles as $detalle)
                    <div class="flex gap-3 md:gap-4 items-center border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                        @if($detalle->producto->imagen)
                            <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                 class="w-14 h-14 md:w-16 md:h-16 object-cover rounded-lg flex-shrink-0">
                        @else
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-gamepad text-gray-600"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate">{{ $detalle->producto->nombre }}</p>
                            <p class="text-gray-400 text-xs mt-1">
                                S/ {{ number_format($detalle->precio_unitario, 2) }} x {{ $detalle->cantidad }}
                            </p>
                        </div>
                        <span class="text-cyan-400 font-bold text-xs md:text-sm flex-shrink-0">
                            S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between">
                <span class="text-white font-bold">Total</span>
                <span class="text-cyan-400 font-bold text-lg">S/ {{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>

        <!-- Dirección -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5">
            <h3 class="text-white font-semibold mb-3 text-sm md:text-base">
                <i class="fas fa-map-marker-alt text-cyan-400 mr-2"></i> Dirección de envío
            </h3>
            <p class="text-gray-400 text-sm">{{ $pedido->direccion_envio }}</p>
        </div>
    </div>

    <!-- Timeline de seguimiento -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6 h-fit">
        <h3 class="text-white font-bold mb-6 text-sm md:text-base">
            <i class="fas fa-route text-cyan-400 mr-2"></i> Seguimiento del pedido
        </h3>

        @php
            $ordenEstados = ['pendiente', 'procesando', 'confirmado', 'enviado', 'entregado', 'cancelado'];
            $estadoActual = $pedido->estado;
            $indexActual  = array_search($estadoActual, $ordenEstados);

            $config = [
                'pendiente'  => ['icon' => 'fas fa-clock',        'color' => 'text-yellow-400',  'bg' => 'bg-yellow-900',  'desc' => 'Pedido recibido y en espera de confirmación.'],
                'procesando' => ['icon' => 'fas fa-cog fa-spin',  'color' => 'text-blue-400',    'bg' => 'bg-blue-900',    'desc' => 'Pedido en proceso de verificación.'],
                'confirmado' => ['icon' => 'fas fa-check',        'color' => 'text-cyan-400',    'bg' => 'bg-cyan-900',    'desc' => 'Pedido confirmado y en preparación.'],
                'enviado'    => ['icon' => 'fas fa-truck',        'color' => 'text-purple-400',  'bg' => 'bg-purple-900',  'desc' => 'Pedido enviado al destino.'],
                'entregado'  => ['icon' => 'fas fa-home',         'color' => 'text-green-400',   'bg' => 'bg-green-900',   'desc' => 'Pedido entregado al cliente.'],
                'cancelado'  => ['icon' => 'fas fa-times',        'color' => 'text-red-400',     'bg' => 'bg-red-900',     'desc' => 'Pedido cancelado.'],
            ];
        @endphp

        @if($pedido->seguimientos->isEmpty())
            <p class="text-gray-400 text-sm">Sin actualizaciones aún.</p>
        @else
            <div class="relative">
                <!-- Línea vertical -->
                <div class="absolute left-3.5 top-0 bottom-0 w-0.5 bg-gray-700"></div>

                <div class="space-y-5">
                    @foreach($pedido->seguimientos->sortBy('created_at') as $seguimiento)
                        @php
                            $c       = $config[$seguimiento->estado] ?? $config['pendiente'];
                            $esActual = $seguimiento->estado === $estadoActual;
                        @endphp
                        <div class="relative flex gap-3 md:gap-4 pl-9 md:pl-10">
                            <!-- Icono -->
                            <div class="absolute left-0 w-7 h-7 {{ $c['bg'] }} rounded-full flex items-center justify-center flex-shrink-0
                                        {{ $esActual ? 'ring-2 ring-offset-2 ring-offset-gray-900 ring-cyan-400' : '' }}">
                                <i class="{{ $c['icon'] }} {{ $c['color'] }} text-xs"></i>
                            </div>

                            <div class="flex-1 pb-1 min-w-0">
                                <!-- Estado + fecha -->
                                <div class="flex items-start justify-between gap-2 flex-wrap">
                                    <p class="text-white text-sm font-semibold capitalize flex items-center gap-2 flex-wrap">
                                        {{ ucfirst($seguimiento->estado) }}
                                        @if($esActual)
                                            <span class="text-xs px-2 py-0.5 bg-cyan-900 text-cyan-400 rounded-full border border-cyan-700 whitespace-nowrap">
                                                Estado actual
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-gray-500 text-xs whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($seguimiento->created_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <!-- Descripción -->
                                <p class="text-gray-400 text-xs mt-0.5">
                                    {{ $seguimiento->descripcion ?? $c['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@endsection