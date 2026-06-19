@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('contenido')

<!-- Breadcrumb -->
<nav class="text-sm text-gray-400 mb-6 overflow-x-auto whitespace-nowrap">
    <a href="{{ route('tienda.index') }}" class="hover:text-cyan-400 transition">Tienda</a>
    <span class="mx-2">/</span>
    <a href="{{ route('tienda.categoria', $producto->categoria->slug) }}"
       class="hover:text-cyan-400 transition">{{ $producto->categoria->nombre }}</a>
    <span class="mx-2">/</span>
    <span class="text-white">{{ $producto->nombre }}</span>
</nav>

<!-- Detalle producto -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-8">

    <!-- Imagen -->
    <div>
        @if($producto->imagen && Storage::disk('public')->exists($producto->imagen))
            <img src="{{ asset('storage/' . $producto->imagen) }}"
                 alt="{{ $producto->nombre }}"
                 class="w-full rounded-xl object-cover max-h-72 md:max-h-96">
        @else
            <div class="w-full h-60 md:h-80 bg-gray-800 rounded-xl flex items-center justify-center">
                <i class="fas fa-gamepad text-gray-600 text-5xl md:text-6xl"></i>
            </div>
        @endif
    </div>

    <!-- Info -->
    <div class="flex flex-col justify-between">
        <div>
            <span class="text-xs text-cyan-400 font-medium uppercase tracking-widest">
                {{ $producto->categoria->nombre }}
            </span>
            <h1 class="text-2xl md:text-3xl font-bold text-white mt-2">{{ $producto->nombre }}</h1>

            <!-- Precio -->
            <div class="mt-4">
                <span class="text-3xl md:text-4xl font-bold text-cyan-400">
                    S/ {{ number_format($producto->precio, 2) }}
                </span>
            </div>

            <!-- Stock -->
            <div class="mt-3">
                @if($producto->stock > 0)
                    <span class="text-green-400 text-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ $producto->stock }} unidades disponibles
                    </span>
                @else
                    <span class="text-red-400 text-sm">
                        <i class="fas fa-times-circle mr-1"></i> Sin stock
                    </span>
                @endif
            </div>

            <!-- Descripción -->
            <div class="mt-6 text-gray-400 text-sm leading-relaxed border-t border-gray-800 pt-4">
                {{ $producto->descripcion }}
            </div>
        </div>

        <!-- Agregar al carrito -->
        <div class="mt-8">
            @if($producto->stock > 0)
                @auth
                    <form action="{{ route('carrito.agregar') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}"
                            class="w-16 md:w-20 bg-gray-800 border border-gray-600 rounded-lg px-2 md:px-3 py-3 text-white text-center focus:outline-none focus:border-cyan-400">
                        <button type="submit"
                            class="flex-1 btn-gamer text-white font-semibold py-3 rounded-lg text-sm md:text-base">
                            <i class="fas fa-cart-plus mr-1 md:mr-2"></i>
                            <span class="hidden sm:inline">Agregar al carrito</span>
                            <span class="sm:hidden">Agregar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block w-full text-center btn-gamer text-white font-semibold py-3 rounded-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i> Inicia sesión para comprar
                    </a>
                @endauth
            @else
                <button disabled
                    class="w-full bg-gray-700 text-gray-500 font-semibold py-3 rounded-lg cursor-not-allowed">
                    Sin stock disponible
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Productos relacionados -->
@if($relacionados->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-lg md:text-xl font-bold text-white mb-6">
            <i class="fas fa-th-large text-cyan-400 mr-2"></i> Productos relacionados
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            @foreach($relacionados as $rel)
                <div class="card-hover bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                    <a href="{{ route('tienda.producto', $rel->slug) }}">
                        @if($rel->imagen && Storage::disk('public')->exists($rel->imagen))
                            <img src="{{ asset('storage/' . $rel->imagen) }}"
                                 alt="{{ $rel->nombre }}"
                                 class="w-full h-28 md:h-36 object-cover">
                        @else
                            <div class="w-full h-28 md:h-36 bg-gray-800 flex items-center justify-center">
                                <i class="fas fa-gamepad text-gray-600 text-2xl"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-2.5 md:p-3">
                        <p class="text-white text-xs md:text-sm font-medium leading-tight line-clamp-2">
                            <a href="{{ route('tienda.producto', $rel->slug) }}"
                               class="hover:text-cyan-400 transition">
                                {{ $rel->nombre }}
                            </a>
                        </p>
                        <p class="text-cyan-400 font-bold text-xs md:text-sm mt-1">
                            S/ {{ number_format($rel->precio, 2) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@endsection