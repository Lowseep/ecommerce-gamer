@extends('layouts.admin')

@section('titulo', 'Pedidos')

@section('contenido')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-white font-bold text-lg flex items-center gap-2">
            <i class="fas fa-shopping-bag text-cyan-400"></i> Pedidos
        </h2>
        <p class="text-gray-500 text-xs mt-1">{{ $pedidos->total() }} pedidos en total</p>
    </div>
</div>

@php
    $colores = [
        'pendiente'  => 'bg-yellow-900 bg-opacity-50 text-yellow-300 border border-yellow-800',
        'procesando' => 'bg-blue-900 bg-opacity-50 text-blue-300 border border-blue-800',
        'confirmado' => 'bg-cyan-900 bg-opacity-50 text-cyan-300 border border-cyan-800',
        'enviado'    => 'bg-purple-900 bg-opacity-50 text-purple-300 border border-purple-800',
        'entregado'  => 'bg-green-900 bg-opacity-50 text-green-300 border border-green-800',
        'cancelado'  => 'bg-red-900 bg-opacity-50 text-red-300 border border-red-800',
    ];
    $iconos = [
        'pendiente'  => 'fas fa-clock',
        'procesando' => 'fas fa-cog',
        'confirmado' => 'fas fa-check',
        'enviado'    => 'fas fa-truck',
        'entregado'  => 'fas fa-home',
        'cancelado'  => 'fas fa-times',
    ];
@endphp

<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

    <!-- Vista TABLA (solo desktop, md+) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr class="text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-center px-4 py-3 w-12">ID</th>
                    <th class="text-left px-4 py-3">Código</th>
                    <th class="text-left px-4 py-3">Cliente</th>
                    <th class="text-center px-4 py-3">Total</th>
                    <th class="text-center px-4 py-3">Estado</th>
                    <th class="text-center px-4 py-3">Fecha</th>
                    <th class="text-center px-4 py-3">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($pedidos as $pedido)
                    @php
                        $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300 border border-gray-700';
                        $icono = $iconos[$pedido->estado] ?? 'fas fa-circle';
                    @endphp
                    <tr class="text-gray-300 hover:bg-gray-800 transition">

                        <!-- ID -->
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-500 font-mono text-xs bg-gray-800 px-2 py-1 rounded-lg">
                                #{{ $pedido->id }}
                            </span>
                        </td>

                        <!-- Código -->
                        <td class="px-4 py-3">
                            <span class="font-mono text-cyan-400 font-medium text-xs tracking-wide">
                                {{ $pedido->codigo }}
                            </span>
                        </td>

                        <!-- Cliente -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-gray-700 rounded-full flex items-center justify-center text-xs font-bold text-gray-300 flex-shrink-0">
                                    {{ strtoupper(substr($pedido->usuario->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $pedido->usuario->nombre }}</p>
                                    <p class="text-gray-500 text-xs">{{ $pedido->usuario->correo }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-4 py-3 text-center">
                            <span class="text-cyan-400 font-bold">
                                S/ {{ number_format($pedido->total, 2) }}
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $color }}">
                                <i class="{{ $icono }} text-xs"></i>
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>

                        <!-- Fecha -->
                        <td class="px-4 py-3 text-center">
                            <p class="text-gray-400 text-xs">{{ $pedido->created_at->format('d/m/Y') }}</p>
                            <p class="text-gray-600 text-xs">{{ $pedido->created_at->format('H:i') }}</p>
                        </td>

                        <!-- Acción -->
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.pedidos.detalle', $pedido->codigo) }}"
                               class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-cyan-900 bg-opacity-40 border border-cyan-700 text-cyan-400 hover:bg-cyan-800 rounded-lg transition">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-gray-500">
                            <i class="fas fa-shopping-bag text-5xl mb-4 block text-gray-700"></i>
                            <p class="text-gray-400">No hay pedidos aún.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Vista TARJETAS (solo móvil) -->
    <div class="md:hidden">
        @forelse($pedidos as $pedido)
            @php
                $color = $colores[$pedido->estado] ?? 'bg-gray-800 text-gray-300 border border-gray-700';
                $icono = $iconos[$pedido->estado] ?? 'fas fa-circle';
            @endphp
            <div class="border-b border-gray-800 p-4 last:border-0">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <span class="font-mono text-cyan-400 font-medium text-xs tracking-wide break-all">
                        {{ $pedido->codigo }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium flex-shrink-0 {{ $color }}">
                        <i class="{{ $icono }} text-xs"></i>
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </div>

                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-xs font-bold text-gray-300 flex-shrink-0">
                        {{ strtoupper(substr($pedido->usuario->nombre, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white text-sm font-medium truncate">{{ $pedido->usuario->nombre }}</p>
                        <p class="text-gray-500 text-xs truncate">{{ $pedido->usuario->correo }}</p>
                    </div>
                    <span class="text-cyan-400 font-bold text-sm flex-shrink-0">
                        S/ {{ number_format($pedido->total, 2) }}
                    </span>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-gray-800">
                    <div class="text-xs text-gray-500">
                        {{ $pedido->created_at->format('d/m/Y') }} · {{ $pedido->created_at->format('H:i') }}
                    </div>
                    <a href="{{ route('admin.pedidos.detalle', $pedido->codigo) }}"
                       class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 bg-cyan-900 bg-opacity-40 border border-cyan-700 text-cyan-400 hover:bg-cyan-800 rounded-lg transition">
                        <i class="fas fa-eye"></i> Ver
                    </a>
                </div>
            </div>
        @empty
            <div class="px-4 py-16 text-center text-gray-500">
                <i class="fas fa-shopping-bag text-5xl mb-4 block text-gray-700"></i>
                <p class="text-gray-400">No hay pedidos aún.</p>
            </div>
        @endforelse
    </div>

    @if($pedidos->hasPages())
        <div class="mt-4 px-4 md:px-6 pb-4 overflow-x-auto">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>

@endsection