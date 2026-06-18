@extends('layouts.admin')

@section('titulo', 'Dashboard')

@section('contenido')

<!-- Métricas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Total Pedidos</p>
                <p class="text-2xl md:text-3xl font-bold text-white mt-1">{{ $totalPedidos }}</p>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shopping-bag text-blue-400 text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Productos</p>
                <p class="text-2xl md:text-3xl font-bold text-white mt-1">{{ $totalProductos }}</p>
                @if($productosSinStock > 0)
                    <p class="text-red-400 text-xs mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        {{ $productosSinStock }} sin stock
                    </p>
                @endif
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box text-purple-400 text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Clientes</p>
                <p class="text-2xl md:text-3xl font-bold text-white mt-1">{{ $totalUsuarios }}</p>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-green-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-green-400 text-lg md:text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">Ingresos</p>
                <p class="text-2xl md:text-3xl font-bold text-white mt-1">
                    S/ {{ number_format($totalIngresos, 0) }}
                </p>
            </div>
            <div class="w-10 h-10 md:w-12 md:h-12 bg-cyan-900 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-dollar-sign text-cyan-400 text-lg md:text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Pedidos recientes -->
<div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-white font-bold text-sm md:text-base">
            <i class="fas fa-clock text-cyan-400 mr-2"></i> Pedidos Recientes
        </h3>
        <a href="{{ route('admin.pedidos.index') }}"
           class="text-cyan-400 text-sm hover:underline">Ver todos</a>
    </div>

    @if($pedidosRecientes->isEmpty())
        <p class="text-gray-400 text-sm text-center py-8">No hay pedidos aún.</p>
    @else
        @php
            $colores = [
                'pendiente'  => 'bg-yellow-900 text-yellow-300',
                'procesando' => 'bg-blue-900 text-blue-300',
                'confirmado' => 'bg-cyan-900 text-cyan-300',
                'enviado'    => 'bg-purple-900 text-purple-300',
                'entregado'  => 'bg-green-900 text-green-300',
                'cancelado'  => 'bg-red-900 text-red-300',
            ];
        @endphp

        <!-- Vista TABLA (solo desktop, md+) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-800">
                        <th class="text-left pb-3">Código</th>
                        <th class="text-left pb-3">Cliente</th>
                        <th class="text-left pb-3">Total</th>
                        <th class="text-left pb-3">Estado</th>
                        <th class="text-left pb-3">Fecha</th>
                        <th class="text-left pb-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($pedidosRecientes as $pedido)
                        @php $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300'; @endphp
                        <tr class="text-gray-300">
                            <td class="py-3 font-mono text-cyan-400">{{ $pedido->codigo }}</td>
                            <td class="py-3">{{ $pedido->usuario->nombre }}</td>
                            <td class="py-3">S/ {{ number_format($pedido->total, 2) }}</td>
                            <td class="py-3">
                                <span class="px-2 py-1 rounded-full text-xs {{ $color }} capitalize">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-500 text-xs">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3">
                                <a href="{{ route('admin.pedidos.detalle', $pedido->codigo) }}"
                                   class="text-cyan-400 hover:underline text-xs">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Vista TARJETAS (solo móvil) -->
        <div class="md:hidden space-y-3">
            @foreach($pedidosRecientes as $pedido)
                @php $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300'; @endphp
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-3">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="font-mono text-cyan-400 text-sm break-all">{{ $pedido->codigo }}</span>
                        <span class="px-2 py-1 rounded-full text-xs {{ $color }} capitalize flex-shrink-0">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-300 truncate">{{ $pedido->usuario->nombre }}</span>
                        <span class="text-white font-bold flex-shrink-0 ml-2">S/ {{ number_format($pedido->total, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-700">
                        <span class="text-gray-500 text-xs">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                        <a href="{{ route('admin.pedidos.detalle', $pedido->codigo) }}"
                           class="text-cyan-400 hover:underline text-xs font-medium">
                            Ver detalle →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection