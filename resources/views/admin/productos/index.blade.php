@extends('layouts.admin')

@section('titulo', 'Productos')

@section('contenido')

{{-- Mensajes --}}
@if(session('success'))
    <div class="mb-4 flex items-center gap-3 bg-green-900 bg-opacity-40 border border-green-700 text-green-400 px-4 py-3 rounded-xl text-sm">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 flex items-center gap-3 bg-red-900 bg-opacity-40 border border-red-700 text-red-400 px-4 py-3 rounded-xl text-sm">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-white font-bold text-lg flex items-center gap-2">
            <i class="fas fa-box text-cyan-400"></i> Productos
        </h2>
        <p class="text-gray-500 text-xs mt-1">{{ $productos->total() }} productos en total</p>
    </div>
    <a href="{{ route('admin.productos.crear') }}"
       class="flex items-center justify-center gap-2 btn-gamer text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-cyan-900/30 border border-cyan-400 border-opacity-30">
        <i class="fas fa-plus"></i> Nuevo Producto
    </a>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

    <!-- Vista TABLA (desktop) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr class="text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-center px-4 py-3 w-12">ID</th>
                    <th class="text-left px-4 py-3">Producto</th>
                    <th class="text-left px-4 py-3">Categoría</th>
                    <th class="text-center px-4 py-3">Precio</th>
                    <th class="text-center px-4 py-3">Stock</th>
                    <th class="text-center px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($productos as $producto)
                    <tr class="text-gray-300 hover:bg-gray-800 transition">
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-500 font-mono text-xs bg-gray-800 px-2 py-1 rounded-lg">
                                #{{ $producto->id }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($producto->imagen && Storage::disk('public')->exists($producto->imagen))
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                         class="w-11 h-11 object-cover rounded-xl border border-gray-700 flex-shrink-0">
                                @else
                                    <div class="w-11 h-11 bg-gray-800 rounded-xl border border-gray-700 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-gamepad text-gray-600 text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-white text-sm">{{ $producto->nombre }}</p>
                                    <p class="text-gray-600 text-xs mt-0.5 truncate max-w-48">{{ Str::limit($producto->descripcion, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2.5 py-1 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-cyan-400 font-bold text-sm">
                                S/ {{ number_format($producto->precio, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($producto->stock > 10)
                                <span class="text-xs px-2.5 py-1 bg-green-900 bg-opacity-50 border border-green-800 text-green-400 rounded-lg">
                                    {{ $producto->stock }} uds
                                </span>
                            @elseif($producto->stock > 0)
                                <span class="text-xs px-2.5 py-1 bg-yellow-900 bg-opacity-50 border border-yellow-800 text-yellow-400 rounded-lg">
                                    {{ $producto->stock }} uds
                                </span>
                            @else
                                <span class="text-xs px-2.5 py-1 bg-red-900 bg-opacity-50 border border-red-800 text-red-400 rounded-lg">
                                    Sin stock
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.productos.editar', $producto->id) }}"
                                   class="flex items-center gap-1.5 text-xs px-3 py-1.5 bg-blue-900 bg-opacity-40 border border-blue-700 text-blue-400 hover:bg-blue-800 rounded-lg transition">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <button type="button"
                                    onclick="abrirModal({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')"
                                    class="flex items-center gap-1.5 text-xs px-3 py-1.5 bg-red-900 bg-opacity-40 border border-red-700 text-red-400 hover:bg-red-800 rounded-lg transition">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-500">
                            <i class="fas fa-box-open text-5xl mb-4 block text-gray-700"></i>
                            <p class="text-gray-400 mb-2">No hay productos registrados</p>
                            <a href="{{ route('admin.productos.crear') }}"
                               class="inline-flex items-center gap-2 btn-gamer text-white px-4 py-2 rounded-xl text-sm font-medium mt-2">
                                <i class="fas fa-plus"></i> Crear primer producto
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Vista TARJETAS (móvil) -->
    <div class="md:hidden">
        @forelse($productos as $producto)
            <div class="border-b border-gray-800 p-4 last:border-0">
                <div class="flex items-center gap-3 mb-3">
                    @if($producto->imagen && Storage::disk('public')->exists($producto->imagen))
                        <img src="{{ asset('storage/' . $producto->imagen) }}"
                             class="w-14 h-14 object-cover rounded-xl border border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-14 h-14 bg-gray-800 rounded-xl border border-gray-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-gamepad text-gray-600"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 font-mono text-xs bg-gray-800 px-1.5 py-0.5 rounded">#{{ $producto->id }}</span>
                            <span class="text-xs px-2 py-0.5 bg-gray-800 border border-gray-700 text-gray-300 rounded-lg truncate">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </div>
                        <p class="font-medium text-white text-sm mt-1 truncate">{{ $producto->nombre }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-cyan-400 font-bold text-base">
                        S/ {{ number_format($producto->precio, 2) }}
                    </span>
                    @if($producto->stock > 10)
                        <span class="text-xs px-2.5 py-1 bg-green-900 bg-opacity-50 border border-green-800 text-green-400 rounded-lg">
                            {{ $producto->stock }} uds
                        </span>
                    @elseif($producto->stock > 0)
                        <span class="text-xs px-2.5 py-1 bg-yellow-900 bg-opacity-50 border border-yellow-800 text-yellow-400 rounded-lg">
                            {{ $producto->stock }} uds
                        </span>
                    @else
                        <span class="text-xs px-2.5 py-1 bg-red-900 bg-opacity-50 border border-red-800 text-red-400 rounded-lg">
                            Sin stock
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.productos.editar', $producto->id) }}"
                       class="flex-1 flex items-center justify-center gap-1.5 text-xs px-3 py-2 bg-blue-900 bg-opacity-40 border border-blue-700 text-blue-400 hover:bg-blue-800 rounded-lg transition">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button"
                        onclick="abrirModal({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')"
                        class="flex-1 flex items-center justify-center gap-1.5 text-xs px-3 py-2 bg-red-900 bg-opacity-40 border border-red-700 text-red-400 hover:bg-red-800 rounded-lg transition">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="px-4 py-16 text-center text-gray-500">
                <i class="fas fa-box-open text-5xl mb-4 block text-gray-700"></i>
                <p class="text-gray-400 mb-2">No hay productos registrados</p>
                <a href="{{ route('admin.productos.crear') }}"
                   class="inline-flex items-center gap-2 btn-gamer text-white px-4 py-2 rounded-xl text-sm font-medium mt-2">
                    <i class="fas fa-plus"></i> Crear primer producto
                </a>
            </div>
        @endforelse
    </div>

    @if($productos->hasPages())
        <div class="mt-4 px-4 md:px-6 pb-4 overflow-x-auto">
            {{ $productos->links() }}
        </div>
    @endif
</div>

{{-- Modal confirmación eliminar --}}
<div id="modalEliminar" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black bg-opacity-70 backdrop-blur-sm" onclick="cerrarModal()"></div>
    <div class="relative bg-gray-900 border border-gray-700 rounded-2xl p-6 w-full max-w-sm shadow-2xl">
        <div class="flex flex-col items-center text-center gap-3">
            <div class="w-14 h-14 bg-red-900 bg-opacity-40 border border-red-700 rounded-full flex items-center justify-center">
                <i class="fas fa-trash text-red-400 text-xl"></i>
            </div>
            <h3 class="text-white font-bold text-lg">¿Eliminar producto?</h3>
            <p class="text-gray-400 text-sm">Estás a punto de eliminar <span id="modalNombreProducto" class="text-white font-semibold"></span>. Esta acción no se puede deshacer.</p>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="cerrarModal()"
                class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 text-gray-300 hover:bg-gray-700 rounded-xl text-sm font-medium transition">
                Cancelar
            </button>
            <form id="formEliminar" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full px-4 py-2.5 bg-red-700 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">
                    Sí, eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModal(id, nombre) {
    document.getElementById('modalNombreProducto').textContent = nombre;
    document.getElementById('formEliminar').action = '{{ url('/admin/productos') }}/' + id;
    const modal = document.getElementById('modalEliminar');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function cerrarModal() {
    const modal = document.getElementById('modalEliminar');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection
