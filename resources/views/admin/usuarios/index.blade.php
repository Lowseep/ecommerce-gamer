@extends('layouts.admin')

@section('titulo', 'Usuarios')

@section('contenido')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-white font-bold text-lg flex items-center gap-2">
            <i class="fas fa-users text-cyan-400"></i> Usuarios
        </h2>
        <p class="text-gray-500 text-xs mt-1">{{ $usuarios->total() }} usuarios registrados</p>
    </div>
</div>

<div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">

    <!-- Vista TABLA (solo desktop, md+) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 border-b border-gray-700">
                <tr class="text-gray-400 text-xs uppercase tracking-wider">
                    <th class="text-center px-4 py-3 w-12">ID</th>
                    <th class="text-left px-4 py-3">Usuario</th>
                    <th class="text-left px-4 py-3">Correo</th>
                    <th class="text-center px-4 py-3">Rol</th>
                    <th class="text-center px-4 py-3">Registro</th>
                    <th class="text-center px-4 py-3">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($usuarios as $usuario)
                    <tr class="text-gray-300 hover:bg-gray-800 transition">

                        <!-- ID -->
                        <td class="px-4 py-3 text-center">
                            <span class="text-gray-500 font-mono text-xs bg-gray-800 px-2 py-1 rounded-lg">
                                #{{ $usuario->id }}
                            </span>
                        </td>

                        <!-- Avatar + nombre -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0
                                    {{ $usuario->rol === 'administrador' ? 'bg-cyan-900 text-cyan-400 border border-cyan-700' : 'bg-gray-700 text-gray-300 border border-gray-600' }}">
                                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white text-sm">{{ $usuario->nombre }}</p>
                                    @if(Auth::id() === $usuario->id)
                                        <p class="text-cyan-500 text-xs">← Tu cuenta</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Correo -->
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $usuario->correo }}</td>

                        <!-- Rol -->
                        <td class="px-4 py-3 text-center">
                            @if($usuario->rol === 'administrador')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs bg-cyan-900 bg-opacity-50 text-cyan-300 border border-cyan-700">
                                    <i class="fas fa-shield-alt text-xs"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs bg-gray-800 text-gray-400 border border-gray-700">
                                    <i class="fas fa-user text-xs"></i> Cliente
                                </span>
                            @endif
                        </td>

                        <!-- Fecha registro -->
                        <td class="px-4 py-3 text-center text-gray-500 text-xs">
                            {{ $usuario->created_at->format('d/m/Y') }}
                        </td>

                        <!-- Cambiar rol -->
                        <td class="px-4 py-3 text-center">
                            @if(Auth::id() !== $usuario->id)
                                <form action="{{ route('admin.usuarios.rol', $usuario->id) }}" method="POST"
                                      class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('POST')
                                    <select name="rol"
                                        class="bg-gray-800 border border-gray-700 rounded-lg px-2 py-1.5 text-white text-xs focus:outline-none focus:border-cyan-400 transition">
                                        <option value="cliente"       {{ $usuario->rol === 'cliente'       ? 'selected' : '' }}>Cliente</option>
                                        <option value="administrador" {{ $usuario->rol === 'administrador' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit"
                                        class="btn-gamer text-white text-xs px-3 py-1.5 rounded-lg font-medium">
                                        Cambiar
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-600 text-xs italic">No editable</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-500">
                            <i class="fas fa-users text-5xl mb-4 block text-gray-700"></i>
                            <p class="text-gray-400">No hay usuarios registrados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Vista TARJETAS (solo móvil) -->
    <div class="md:hidden">
        @forelse($usuarios as $usuario)
            <div class="border-b border-gray-800 p-4 last:border-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0
                        {{ $usuario->rol === 'administrador' ? 'bg-cyan-900 text-cyan-400 border border-cyan-700' : 'bg-gray-700 text-gray-300 border border-gray-600' }}">
                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-white text-sm truncate">{{ $usuario->nombre }}</p>
                        <p class="text-gray-500 text-xs truncate">{{ $usuario->correo }}</p>
                        @if(Auth::id() === $usuario->id)
                            <p class="text-cyan-500 text-xs">← Tu cuenta</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between mb-3">
                    @if($usuario->rol === 'administrador')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs bg-cyan-900 bg-opacity-50 text-cyan-300 border border-cyan-700">
                            <i class="fas fa-shield-alt text-xs"></i> Admin
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs bg-gray-800 text-gray-400 border border-gray-700">
                            <i class="fas fa-user text-xs"></i> Cliente
                        </span>
                    @endif
                    <span class="text-gray-500 text-xs">
                        <i class="far fa-calendar mr-1"></i>{{ $usuario->created_at->format('d/m/Y') }}
                    </span>
                </div>

                @if(Auth::id() !== $usuario->id)
                    <form action="{{ route('admin.usuarios.rol', $usuario->id) }}" method="POST"
                          class="flex items-center gap-2 pt-3 border-t border-gray-800">
                        @csrf
                        @method('POST')
                        <select name="rol"
                            class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-2 py-2 text-white text-xs focus:outline-none focus:border-cyan-400 transition">
                            <option value="cliente"       {{ $usuario->rol === 'cliente'       ? 'selected' : '' }}>Cliente</option>
                            <option value="administrador" {{ $usuario->rol === 'administrador' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit"
                            class="btn-gamer text-white text-xs px-4 py-2 rounded-lg font-medium flex-shrink-0">
                            Cambiar
                        </button>
                    </form>
                @else
                    <div class="pt-3 border-t border-gray-800 text-center">
                        <span class="text-gray-600 text-xs italic">No editable</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="px-4 py-16 text-center text-gray-500">
                <i class="fas fa-users text-5xl mb-4 block text-gray-700"></i>
                <p class="text-gray-400">No hay usuarios registrados.</p>
            </div>
        @endforelse
    </div>

    @if($usuarios->hasPages())
        <div class="mt-4 px-4 md:px-6 pb-4 overflow-x-auto">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>

@endsection