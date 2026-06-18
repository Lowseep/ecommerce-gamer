<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
</head>
<body class="bg-gray-950 text-white min-h-screen flex">

    <!-- Overlay oscuro (solo móvil, cuando el sidebar está abierto) -->
    <div id="sidebarOverlay" onclick="toggleSidebar()"
         class="hidden fixed inset-0 bg-black bg-opacity-60 z-30 lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 bg-gray-900 border-r border-gray-800 min-h-screen flex flex-col fixed left-0 top-0 z-40
               transform -translate-x-full lg:translate-x-0 transition-transform duration-300">

        <!-- Logo -->
        <div class="p-6 border-b border-gray-800 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <i class="fas fa-gamepad text-cyan-400 text-xl"></i>
                <span class="font-bold text-cyan-400">Fsociety</span>
            </a>
            <!-- Botón cerrar (solo móvil) -->
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-xs text-gray-500 px-6 pb-3 -mt-3">Panel Administración</p>

        <!-- Admin info -->
        <div class="p-4 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-cyan-500 rounded-full flex items-center justify-center text-black font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->nombre }}</p>
                    <p class="text-xs text-cyan-400">Administrador</p>
                </div>
            </div>
        </div>

        <!-- Menú -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line w-4"></i> Dashboard
            </a>
            <a href="{{ route('admin.productos.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.productos*') ? 'active' : '' }}">
                <i class="fas fa-box w-4"></i> Productos
            </a>
            <a href="{{ route('admin.pedidos.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag w-4"></i> Pedidos
            </a>
            <a href="{{ route('admin.usuarios.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
                <i class="fas fa-users w-4"></i> Usuarios
            </a>
            <a href="{{ route('admin.sistema.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.sistema*') ? 'active' : '' }}">
                <i class="fas fa-microchip w-4"></i> Monitor SO
            </a>

            <hr class="border-gray-800 my-2">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="sidebar-link w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-400 text-sm text-left">
                    <i class="fas fa-sign-out-alt w-4"></i> Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="lg:ml-64 flex-1 flex flex-col min-h-screen w-full">

        <!-- Header -->
        <header class="bg-gray-900 border-b border-gray-800 px-4 md:px-6 py-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <!-- Botón hamburguesa (solo móvil) -->
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-300 hover:text-white flex-shrink-0">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h1 class="text-base md:text-lg font-semibold text-white truncate">@yield('titulo', 'Dashboard')</h1>
            </div>
            <span class="text-xs md:text-sm text-gray-400 flex-shrink-0">
                <i class="fas fa-clock mr-1"></i>
                <span class="hidden sm:inline">{{ now()->format('d/m/Y H:i') }}</span>
                <span class="sm:hidden">{{ now()->format('H:i') }}</span>
            </span>
        </header>

        <!-- Alertas -->
        <div class="px-4 md:px-6 pt-4">
            @if(session('success'))
                <div id="alertSuccess"
                    class="bg-green-900 border border-green-600 text-green-300 px-4 py-3 rounded-xl mb-4 flex items-center justify-between gap-2 text-sm transition-all duration-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-400"></i>
                        {{ session('success') }}
                    </span>
                    <button onclick="cerrarAlerta('alertSuccess')" class="text-green-400 hover:text-green-200 transition ml-4 flex-shrink-0">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div id="alertError"
                    class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded-xl mb-4 flex items-center justify-between gap-2 text-sm transition-all duration-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                        {{ session('error') }}
                    </span>
                    <button onclick="cerrarAlerta('alertError')" class="text-red-400 hover:text-red-200 transition ml-4 flex-shrink-0">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- Contenido -->
        <main class="flex-1 p-4 md:p-6">
            @yield('contenido')
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-800 px-4 md:px-6 py-3 text-center text-xs text-gray-600">
        Fsociety Admin © {{ date('Y') }}
        </footer>
    </div>

    <script>
    // ── Sidebar móvil ──────────────────────────────────────────
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }

    // ── Alertas ────────────────────────────────────────────────
    function cerrarAlerta(id) {
        const alerta = document.getElementById(id);
        if (alerta) {
            alerta.style.opacity = '0';
            alerta.style.transform = 'translateY(-10px)';
            setTimeout(() => alerta.remove(), 500);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['alertSuccess', 'alertError'].forEach(function(id) {
            const alerta = document.getElementById(id);
            if (alerta) {
                setTimeout(() => cerrarAlerta(id), 2000);
            }
        });
    });
    </script>

</body>
</html>