@extends('layouts.admin')

@section('titulo', 'Pedido ' . $pedido->codigo)

@section('contenido')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.pedidos.index') }}" class="text-gray-400 hover:text-white transition flex-shrink-0">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h2 class="text-white font-bold text-base md:text-lg break-all">Pedido: {{ $pedido->codigo }}</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Detalle -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Productos del pedido -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6">
            <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
                <i class="fas fa-box text-cyan-400 mr-2"></i> Productos
            </h3>
            <div class="space-y-4">
                @foreach($pedido->detalles as $detalle)
                    <div class="flex gap-3 md:gap-4 items-center border-b border-gray-800 pb-4 last:border-0 last:pb-0">
                        @if($detalle->producto->imagen)
                            <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                 class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-lg flex-shrink-0">
                        @else
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-gamepad text-gray-600"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-medium text-sm truncate">{{ $detalle->producto->nombre }}</p>
                            <p class="text-gray-400 text-xs">
                                S/ {{ number_format($detalle->precio_unitario, 2) }} x {{ $detalle->cantidad }}
                            </p>
                        </div>
                        <span class="text-cyan-400 font-bold text-xs md:text-sm flex-shrink-0">
                            S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-700 flex justify-between">
                <span class="text-white font-bold">Total</span>
                <span class="text-cyan-400 font-bold text-lg">S/ {{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>

        <!-- Info cliente -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5">
            <h3 class="text-white font-semibold mb-3 text-sm md:text-base">
                <i class="fas fa-user text-cyan-400 mr-2"></i> Cliente
            </h3>
            <p class="text-gray-300 text-sm truncate">{{ $pedido->usuario->nombre }}</p>
            <p class="text-gray-400 text-sm mt-1 truncate">{{ $pedido->usuario->correo }}</p>
            <p class="text-gray-400 text-sm mt-2 flex items-start gap-1.5">
                <i class="fas fa-map-marker-alt text-cyan-400 mt-0.5 flex-shrink-0"></i>
                <span>{{ $pedido->direccion_envio }}</span>
            </p>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="space-y-4">

        <!-- Cambiar estado -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5">
            <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
                <i class="fas fa-edit text-cyan-400 mr-2"></i> Cambiar Estado
            </h3>

            @php
                $colores = [
                    'pendiente'  => 'bg-yellow-900 text-yellow-300 border border-yellow-700',
                    'procesando' => 'bg-blue-900 text-blue-300 border border-blue-700',
                    'confirmado' => 'bg-cyan-900 text-cyan-300 border border-cyan-700',
                    'enviado'    => 'bg-purple-900 text-purple-300 border border-purple-700',
                    'entregado'  => 'bg-green-900 text-green-300 border border-green-700',
                    'cancelado'  => 'bg-red-900 text-red-300 border border-red-700',
                ];
                $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300';

                $orden = ['pendiente', 'procesando', 'confirmado', 'enviado', 'entregado'];
                $indexActual = array_search($pedido->estado, $orden);
                $bloqueado = in_array($pedido->estado, ['entregado', 'cancelado']);
            @endphp

            <div class="mb-4 flex items-center gap-2 flex-wrap">
                <span class="text-xs text-gray-400">Estado actual:</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $color }} capitalize">
                    {{ ucfirst($pedido->estado) }}
                </span>
            </div>

            @if($bloqueado)
                <div class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-400 flex items-center gap-2">
                    <i class="fas fa-lock text-gray-500 flex-shrink-0"></i>
                    @if($pedido->estado === 'entregado')
                        Pedido entregado — no se puede modificar.
                    @else
                        Pedido cancelado — no se puede modificar.
                    @endif
                </div>
            @else
                <form action="{{ route('admin.pedidos.estado', $pedido->codigo) }}" method="POST">
                    @csrf
                    <select name="estado" required
                        class="w-full bg-gray-800 border border-gray-600 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:border-cyan-400 mb-3">

                        @foreach($orden as $index => $estado)
                            @if($index > $indexActual)
                                <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                            @endif
                        @endforeach
                        <option value="cancelado">Cancelar pedido</option>
                    </select>

                    <button type="submit"
                        class="w-full btn-gamer text-white py-2.5 rounded-lg text-sm font-medium">
                        <i class="fas fa-save mr-2"></i> Actualizar Estado
                    </button>
                </form>
            @endif
        </div>
        <!-- Timeline seguimiento -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5">
            <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
                <i class="fas fa-route text-cyan-400 mr-2"></i> Historial
            </h3>

            @if($pedido->seguimientos->isEmpty())
                <p class="text-gray-400 text-sm">Sin historial aún.</p>
            @else
                <div class="relative">
                    <div class="absolute left-3 top-0 bottom-0 w-px bg-gray-700"></div>
                    <div class="space-y-5">
                            @foreach($pedido->seguimientos->sortBy('created_at') as $seg)
                            @php
                                $iconos = [
                                    'pendiente'  => ['fas fa-clock', 'text-yellow-400', 'bg-yellow-900'],
                                    'procesando' => ['fas fa-cog', 'text-blue-400', 'bg-blue-900'],
                                    'confirmado' => ['fas fa-check', 'text-cyan-400', 'bg-cyan-900'],
                                    'enviado'    => ['fas fa-truck', 'text-purple-400', 'bg-purple-900'],
                                    'entregado'  => ['fas fa-home', 'text-green-400', 'bg-green-900'],
                                    'cancelado'  => ['fas fa-times', 'text-red-400', 'bg-red-900'],
                                ];
                                $icono = $iconos[$seg->estado] ?? ['fas fa-circle', 'text-gray-400', 'bg-gray-800'];
                            @endphp
                            <div class="relative flex gap-3 pl-8">
                                <div class="absolute left-0 w-6 h-6 {{ $icono[2] }} rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $icono[0] }} {{ $icono[1] }} text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white text-xs font-medium capitalize">{{ $seg->estado }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ $seg->descripcion }}</p>
                                    <p class="text-gray-600 text-xs mt-0.5">
                                        {{ $seg->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection