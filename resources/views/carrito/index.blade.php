@extends('layouts.app')

@section('titulo', 'Mi Carrito')

@section('contenido')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('tienda.index') }}"
    class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left text-sm"></i>
    </a>
    <h2 class="text-xl font-bold text-white flex items-center gap-2 flex-wrap">
        <i class="fas fa-shopping-cart text-cyan-400"></i> Mi Carrito
        @if($carrito && $carrito->detalles->isNotEmpty())
            <span class="text-sm font-normal text-gray-400">
                ({{ $carrito->detalles->sum('cantidad') }} producto{{ $carrito->detalles->sum('cantidad') > 1 ? 's' : '' }})
            </span>
        @endif
    </h2>
</div>

@if(!$carrito || $carrito->detalles->isEmpty())
    <div class="text-center py-16 md:py-20 bg-gray-900 border border-gray-800 rounded-2xl px-4">
        <i class="fas fa-cart-arrow-down text-gray-600 text-5xl md:text-6xl mb-4"></i>
        <p class="text-gray-400 text-lg mb-2">Tu carrito está vacío</p>
        <p class="text-gray-600 text-sm mb-5">Agrega productos para comenzar tu compra</p>
        <a href="{{ route('tienda.index') }}"
           class="inline-flex items-center gap-2 btn-gamer text-white px-6 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-store"></i> Ir a la tienda
        </a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Lista productos -->
        <div class="lg:col-span-2 space-y-3">
            @foreach($carrito->detalles as $detalle)
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">

                    <!-- Fila superior: imagen + info (siempre en fila) -->
                    <div class="flex gap-4">
                        <!-- Imagen -->
                        <a href="{{ route('tienda.producto', $detalle->producto->slug) }}" class="flex-shrink-0">
                            @if($detalle->producto->imagen)
                                <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                     alt="{{ $detalle->producto->nombre }}"
                                     class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-xl border border-gray-700">
                            @else
                                <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-800 rounded-xl flex items-center justify-center border border-gray-700">
                                    <i class="fas fa-gamepad text-gray-600 text-2xl"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('tienda.producto', $detalle->producto->slug) }}"
                               class="text-white font-medium text-sm hover:text-cyan-400 transition line-clamp-2">
                                {{ $detalle->producto->nombre }}
                            </a>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $detalle->producto->categoria->nombre }}</p>
                            <p class="text-cyan-400 font-bold mt-1">S/ {{ number_format($detalle->precio_unitario, 2) }}</p>
                        </div>

                        <!-- Eliminar (visible arriba a la derecha siempre) -->
                        <form action="{{ route('carrito.quitar') }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <input type="hidden" name="detalle_id" value="{{ $detalle->id }}">
                            <input type="hidden" name="accion" value="eliminar">
                            <button type="submit"
                                class="text-red-400 hover:text-red-300 transition p-1.5 rounded-lg hover:bg-red-900 hover:bg-opacity-20"
                                title="Eliminar">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Fila inferior: controles cantidad + subtotal -->
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-800">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden">
                                <!-- Restar -->
                                <form action="{{ route('carrito.quitar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="detalle_id" value="{{ $detalle->id }}">
                                    <input type="hidden" name="accion" value="restar">
                                    <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition text-sm">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                </form>

                                <span class="w-8 text-center text-white text-sm font-medium">{{ $detalle->cantidad }}</span>

                                <!-- Sumar -->
                                @if($detalle->cantidad < $detalle->producto->stock)
                                    <form action="{{ route('carrito.agregar') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="producto_id" value="{{ $detalle->producto_id }}">
                                        <input type="hidden" name="cantidad" value="1">
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition text-sm">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-600 text-xs cursor-not-allowed"
                                          title="Stock máximo alcanzado">
                                        <i class="fas fa-plus text-xs"></i>
                                    </span>
                                @endif
                            </div>

                            @if($detalle->cantidad >= $detalle->producto->stock)
                                <span class="text-yellow-500 text-xs hidden sm:inline">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Máximo
                                </span>
                            @endif
                        </div>

                        <!-- Subtotal -->
                        <p class="text-white font-bold text-sm">
                            S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                        </p>
                    </div>
                </div>
            @endforeach

            <!-- Vaciar carrito -->
            <div class="flex justify-end">
                <button onclick="document.getElementById('modalVaciar').classList.remove('hidden')"
                    class="text-gray-500 hover:text-red-400 text-xs transition flex items-center gap-1">
                    <i class="fas fa-trash-alt"></i> Vaciar carrito
                </button>
            </div>
        </div>

        <!-- Resumen -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 h-fit lg:sticky lg:top-24">
            <h3 class="text-white font-bold text-base mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-cyan-400"></i> Resumen del pedido
            </h3>

            <!-- Detalle líneas -->
            <div class="space-y-2 mb-4">
                @foreach($carrito->detalles as $detalle)
                    <div class="flex justify-between text-xs text-gray-400">
                        <span class="truncate max-w-[60%]">
                            {{ Str::limit($detalle->producto->nombre, 25) }}
                            <span class="text-gray-600">x{{ $detalle->cantidad }}</span>
                        </span>
                        <span class="flex-shrink-0 ml-2">S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <hr class="border-gray-800 mb-4">

            <!-- Subtotal -->
            <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>Subtotal ({{ $carrito->detalles->sum('cantidad') }} items)</span>
                <span>S/ {{ number_format($total, 2) }}</span>
            </div>

            <!-- Envío -->
            <div class="flex justify-between text-sm text-gray-400 mb-1">
                <span class="flex items-center gap-1">
                    <i class="fas fa-truck text-cyan-400 text-xs"></i> Envío
                </span>
                <span class="text-cyan-400 font-medium text-xs">Se calcula al finalizar</span>
            </div>
            <p class="text-gray-600 text-xs mb-3">
                <i class="fas fa-map-marker-alt mr-1"></i> Enviamos a todo el Perú
            </p>
            <hr class="border-gray-800 mb-3">

            <!-- Total -->
            <div class="flex justify-between text-white font-bold text-lg mb-5">
                <span>Total</span>
                <span class="text-cyan-400">S/ {{ number_format($total, 2) }}</span>
            </div>

            <a href="{{ route('checkout.index') }}"
               class="block w-full text-center btn-gamer text-white font-semibold py-3 rounded-xl text-sm">
                <i class="fas fa-credit-card mr-2"></i> Proceder al pago
            </a>

            <a href="{{ route('tienda.index') }}"
               class="mt-3 block w-full text-center text-gray-400 hover:text-white text-sm py-2.5 rounded-xl border border-gray-700 hover:border-gray-500 transition">
                <i class="fas fa-arrow-left mr-2"></i> Seguir comprando
            </a>
        </div>
    </div>
@endif

<!-- Modal vaciar carrito -->
<div id="modalVaciar"
    class="hidden fixed inset-0 z-50 flex items-center justify-center"
    style="background:rgba(0,0,0,0.7);">
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-red-900 bg-opacity-40 border border-red-700 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-trash-alt text-red-400 text-xl"></i>
            </div>
            <h3 class="text-white font-bold text-lg">¿Vaciar carrito?</h3>
            <p class="text-gray-400 text-sm mt-1">Se eliminarán todos los productos de tu carrito. Esta acción no se puede deshacer.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('modalVaciar').classList.add('hidden')"
                class="flex-1 border border-gray-700 hover:border-gray-500 text-gray-400 hover:text-white py-2.5 rounded-xl text-sm font-medium transition">
                Cancelar
            </button>
            <form action="{{ route('carrito.vaciar') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-500 text-white py-2.5 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-trash-alt mr-1"></i> Sí, vaciar
                </button>
            </form>
        </div>
    </div>
</div>

@endsection