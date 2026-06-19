@extends('layouts.app')

@section('titulo', isset($categoria) ? $categoria->nombre : 'Catálogo')

@section('contenido')

<!-- Barra superior con título y botón filtros -->
<div class="flex items-start sm:items-center justify-between mb-5 gap-3">
    <div class="min-w-0">
        <h2 class="text-lg md:text-xl font-bold text-white truncate">
            @if(isset($categoria))
                <i class="fas fa-tag text-cyan-400 mr-2"></i>{{ $categoria->nombre }}
            @elseif(request('buscar'))
                Resultados para "<span class="text-cyan-400">{{ request('buscar') }}</span>"
            @else
                <i class="fas fa-fire text-cyan-400 mr-2"></i>Todos los Productos
            @endif
        </h2>
        <p class="text-gray-500 text-xs mt-1">{{ $productos->total() }} productos encontrados</p>
    </div>

    <!-- Botón filtros -->
    <div class="relative flex-shrink-0" id="filtrosDropdown">
        <button onclick="toggleFiltros()"
            class="flex items-center gap-1.5 md:gap-2 px-3 md:px-4 py-2.5 bg-gray-900 border border-gray-700 hover:border-cyan-500 text-gray-300 hover:text-cyan-400 rounded-xl text-sm font-medium transition">
            <i class="fas fa-sliders-h text-cyan-400"></i>
            <span class="hidden sm:inline">Filtros</span>
            @if(request('orden') || isset($categoria))
                <span class="w-2 h-2 bg-cyan-400 rounded-full"></span>
            @endif
            <i class="fas fa-chevron-down text-xs" id="filtrosArrow"></i>
        </button>

        <!-- Panel desplegable -->
        <div id="filtrosPanel"
             class="hidden absolute right-0 top-full mt-2 w-[calc(100vw-2rem)] sm:w-72 max-w-72 bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl z-40 overflow-hidden">

            <div class="p-4 bg-gray-800 border-b border-gray-700 flex items-center justify-between">
                <span class="text-white font-semibold text-sm flex items-center gap-2">
                    <i class="fas fa-sliders-h text-cyan-400"></i> Filtrar productos
                </span>
                <button onclick="toggleFiltros()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <div class="p-4 space-y-5">

                <!-- Categorías -->
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">
                        <i class="fas fa-th-large mr-1 text-cyan-500"></i> Categorías
                    </p>
                    <div class="space-y-1">
                        <a href="{{ route('tienda.index', request()->except('categoria')) }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                                  {{ !isset($categoria) ? 'bg-cyan-900 bg-opacity-40 text-cyan-400 border border-cyan-800' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-th-large w-4 text-center text-xs"></i>
                            Todos los productos
                            @if(!isset($categoria))
                                <i class="fas fa-check ml-auto text-cyan-400 text-xs"></i>
                            @endif
                        </a>
                        @foreach(\App\Models\Categoria::all() as $cat)
                            @php
                                $iconos = [
                                    'mouses'      => 'fas fa-mouse',
                                    'teclados'    => 'fas fa-keyboard',
                                    'audifonos'   => 'fas fa-headphones',
                                    'mousepads'   => 'far fa-square',
                                    'camaras-web' => 'fas fa-video',
                                    'microfonos'  => 'fas fa-microphone',
                                ];
                                $icono = $iconos[$cat->slug] ?? 'fas fa-gamepad';
                                $activo = isset($categoria) && $categoria->id === $cat->id;
                            @endphp
                            <a href="{{ route('tienda.categoria', $cat->slug) }}"
                               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                                      {{ $activo ? 'bg-cyan-900 bg-opacity-40 text-cyan-400 border border-cyan-800' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <i class="{{ $icono }} w-4 text-center text-xs"></i>
                                {{ $cat->nombre }}
                                @if($activo)
                                    <i class="fas fa-check ml-auto text-cyan-400 text-xs"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Ordenar -->
                <div class="border-t border-gray-800 pt-4">
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">
                        <i class="fas fa-sort mr-1 text-cyan-500"></i> Ordenar por precio
                    </p>
                    <div class="space-y-1">
                        @php
                            $base = isset($categoria)
                                ? route('tienda.categoria', $categoria->slug)
                                : route('tienda.index');
                        @endphp
                        <a href="{{ $base }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                                  {{ !request('orden') ? 'bg-cyan-900 bg-opacity-40 text-cyan-400 border border-cyan-800' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-clock w-4 text-center text-xs"></i>
                            Más recientes
                            @if(!request('orden'))
                                <i class="fas fa-check ml-auto text-cyan-400 text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ $base }}?orden=precio_asc"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                                  {{ request('orden') === 'precio_asc' ? 'bg-cyan-900 bg-opacity-40 text-cyan-400 border border-cyan-800' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-arrow-up w-4 text-center text-xs"></i>
                            Menor precio primero
                            @if(request('orden') === 'precio_asc')
                                <i class="fas fa-check ml-auto text-cyan-400 text-xs"></i>
                            @endif
                        </a>
                        <a href="{{ $base }}?orden=precio_desc"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                                  {{ request('orden') === 'precio_desc' ? 'bg-cyan-900 bg-opacity-40 text-cyan-400 border border-cyan-800' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fas fa-arrow-down w-4 text-center text-xs"></i>
                            Mayor precio primero
                            @if(request('orden') === 'precio_desc')
                                <i class="fas fa-check ml-auto text-cyan-400 text-xs"></i>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Limpiar filtros -->
                @if(request('orden') || isset($categoria) || request('buscar'))
                    <div class="border-t border-gray-800 pt-3">
                        <a href="{{ route('tienda.index') }}"
                           class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl text-sm text-red-400 border border-red-900 hover:bg-red-900 hover:bg-opacity-20 transition">
                            <i class="fas fa-times-circle"></i> Limpiar filtros
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Grid productos -->
@if($productos->isEmpty())
    <div class="text-center py-16 md:py-20 bg-gray-900 border border-gray-800 rounded-2xl px-4">
        <i class="fas fa-box-open text-gray-600 text-5xl mb-4"></i>
        <p class="text-gray-400">No hay productos disponibles</p>
        <a href="{{ route('tienda.index') }}" class="text-cyan-400 hover:underline text-sm mt-2 inline-block">
            Ver todos los productos
        </a>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
        @foreach($productos as $producto)
        <div class="card-hover bg-white rounded-xl overflow-hidden flex flex-col border border-gray-100 shadow-sm"
             style="background:#1a1a2e; border:1px solid #2a2a3e;">

            <!-- Imagen -->
            <a href="{{ route('tienda.producto', $producto->slug) }}" class="block relative overflow-hidden"
               style="aspect-ratio:1/1;">
                @if($producto->imagen && Storage::disk('public')->exists($producto->imagen))
                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                         alt="{{ $producto->nombre }}"
                         class="w-full h-full object-cover hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-gamepad text-gray-600 text-4xl"></i>
                    </div>
                @endif

                <!-- Badge categoría -->
                <span class="absolute top-2 left-2 text-xs px-2 py-0.5 rounded-full font-medium"
                      style="background:rgba(0,212,255,0.15); color:#00d4ff; border:1px solid rgba(0,212,255,0.3);">
                    {{ $producto->categoria->nombre }}
                </span>

                <!-- Badge stock bajo -->
                @if($producto->stock <= 3)
                    <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full font-medium bg-red-500 text-white">
                        ¡Últimas!
                    </span>
                @endif
            </a>

            <!-- Info -->
            <div class="p-2.5 md:p-3 flex flex-col flex-1">
                <h3 class="text-white font-medium text-xs md:text-sm leading-snug mb-1">
                    <a href="{{ route('tienda.producto', $producto->slug) }}"
                    class="hover:text-cyan-400 transition line-clamp-2">
                        {{ $producto->nombre }}
                    </a>
                </h3>
                <p class="text-gray-500 text-xs leading-relaxed mb-2 hidden sm:block" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $producto->descripcion }}
                </p>

                <!-- Precio -->
                <div class="mt-auto">
                    <p class="text-cyan-400 font-bold text-base md:text-lg">
                        S/ {{ number_format($producto->precio, 2) }}
                    </p>
                    <p class="text-gray-500 text-xs mt-0.5">
                        <i class="fas fa-cubes mr-1"></i>{{ $producto->stock }} disponibles
                    </p>
                </div>

                <!-- Botón agregar -->
                @auth
                    <form action="{{ route('carrito.agregar') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        <input type="hidden" name="cantidad" value="1">
                        <button type="submit"
                            class="w-full text-white text-xs py-2 rounded-lg font-semibold flex items-center justify-center gap-1.5 transition"
                            style="background:linear-gradient(135deg,#00d4ff,#0099cc);">
                            <i class="fas fa-cart-plus"></i>
                            <span class="hidden sm:inline">Agregar al carrito</span>
                            <span class="sm:hidden">Agregar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="mt-2 block w-full text-center text-xs py-2 rounded-lg transition"
                       style="border:1px solid #2a2a3e; color:#6b7280;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Ingresar para comprar
                    </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 overflow-x-auto">{{ $productos->links() }}</div>
@endif

<script>
function toggleFiltros() {
    const panel = document.getElementById('filtrosPanel');
    const arrow = document.getElementById('filtrosArrow');
    panel.classList.toggle('hidden');
    arrow.classList.toggle('fa-chevron-down');
    arrow.classList.toggle('fa-chevron-up');
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('filtrosDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        document.getElementById('filtrosPanel').classList.add('hidden');
        const arrow = document.getElementById('filtrosArrow');
        arrow.classList.add('fa-chevron-down');
        arrow.classList.remove('fa-chevron-up');
    }
});
</script>

@endsection