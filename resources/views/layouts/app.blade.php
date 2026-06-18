<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Fsociety')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
</head>
<body class="min-h-screen text-white">

    <!-- NAVBAR -->
    <nav class="main-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3">

            <!-- Fila 1: Logo + Acciones (siempre visible) -->
            <div class="flex items-center gap-4">

                <!-- Logo -->
                <a href="{{ route('tienda.index') }}" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-8 h-8 bg-cyan-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-gamepad text-black text-sm"></i>
                    </div>
                    <span class="text-xl font-black tracking-tight neon-text text-cyan-400">Fsociety</span>
                </a>

                <!-- Buscador centrado (oculto en móvil, va en fila 2) -->
                <div class="hidden md:flex flex-1 justify-center">
                    @if(!request()->routeIs('carrito.index', 'checkout.*', 'pedidos.*'))
                        <form action="{{ route('tienda.index') }}" method="GET" class="flex w-full max-w-xl">
                            <input
                                type="text"
                                name="buscar"
                                value="{{ request('buscar') }}"
                                placeholder="Busca mouses, teclados, audífonos..."
                                class="search-input flex-1 bg-gray-900 border border-gray-700 border-r-0 rounded-l-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 transition"
                            >
                            <button type="submit" class="btn-gamer text-white px-5 py-2.5 rounded-r-xl text-sm font-medium flex-shrink-0">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Espaciador en móvil para empujar acciones a la derecha -->
                <div class="flex-1 md:hidden"></div>

                <!-- Acciones -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    @auth
                        <!-- Carrito -->
                        @if(Auth::user()->rol !== 'administrador')
                            <a href="{{ route('carrito.index') }}"
                            class="relative flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-700 text-gray-300 hover:border-cyan-500 hover:text-cyan-400 transition text-sm">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="hidden md:block">Carrito</span>
                                @php
                                    $totalItems = app(\App\Services\CarritoService::class)->contarItems();
                                @endphp
                                @if($totalItems > 0)
                                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full text-xs font-bold flex items-center justify-center text-black"
                                        style="background:#00d4ff;">
                                        {{ $totalItems > 99 ? '99+' : $totalItems }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        <!-- Dropdown usuario -->
                        <div class="relative" id="userDropdown">
                            <button onclick="toggleUser()"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-700 text-gray-300 hover:border-cyan-500 hover:text-cyan-400 transition text-sm">
                                <div class="w-6 h-6 bg-cyan-500 rounded-full flex items-center justify-center text-black text-xs font-bold">
                                    {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                                </div>
                                <span class="hidden md:block max-w-20 truncate">{{ explode(' ', Auth::user()->nombre)[0] }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="userMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl z-50 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-800 border-b border-gray-700">
                                    <p class="text-white text-sm font-semibold">{{ Auth::user()->nombre }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ Auth::user()->correo }}</p>
                                </div>
                                <div class="py-2">
                                        @if(Auth::user()->rol !== 'administrador')
                                            <a href="{{ route('pedidos.index') }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-cyan-400 hover:bg-gray-800 transition">
                                                <i class="fas fa-box w-4 text-center"></i> Mis Pedidos
                                            </a>
                                        @endif

                                        @if(Auth::user()->rol === 'administrador')
                                        <a href="{{ route('admin.dashboard') }}"
                                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-cyan-400 hover:bg-gray-800 transition">
                                            <i class="fas fa-cog text-cyan-500 w-4 text-center"></i> Panel Admin
                                        </a>
                                    @endif
                                    <hr class="border-gray-700 my-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-gray-800 transition">
                                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Cerrar sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Ingresar -->
                        <a href="{{ route('login') }}"
                           class="flex items-center gap-2 px-3 md:px-4 py-2 rounded-xl border border-gray-700 text-gray-300 hover:border-cyan-500 hover:text-cyan-400 transition text-sm font-medium">
                            <i class="fas fa-sign-in-alt text-cyan-500"></i>
                            <span class="hidden md:block">Ingresar</span>
                        </a>

                        <!-- Registrarse -->
                        <a href="{{ route('registro') }}"
                           class="flex items-center gap-2 btn-gamer text-white px-3 md:px-4 py-2 rounded-xl text-sm font-medium">
                            <i class="fas fa-user-plus"></i>
                            <span class="hidden md:block">Registrarse</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Fila 2: Buscador en móvil (debajo del logo) -->
            <div class="md:hidden mt-3">
                @if(!request()->routeIs('carrito.index', 'checkout.*', 'pedidos.*'))
                    <form action="{{ route('tienda.index') }}" method="GET" class="flex w-full">
                        <input
                            type="text"
                            name="buscar"
                            value="{{ request('buscar') }}"
                            placeholder="Busca mouses, teclados..."
                            class="search-input flex-1 bg-gray-900 border border-gray-700 border-r-0 rounded-l-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 transition"
                        >
                        <button type="submit" class="btn-gamer text-white px-5 py-2.5 rounded-r-xl text-sm font-medium flex-shrink-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <!-- ALERTAS -->
    @if(session('success'))
        <div id="toast"
            style="position:fixed; top:80px; left:50%; transform:translateX(-50%); z-index:9999;
                    background:#1a1a2e; border:1px solid rgba(0,212,255,0.3);
                    color:#00d4ff; padding:10px 20px; border-radius:999px;
                    font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;
                    box-shadow:0 4px 20px rgba(0,212,255,0.2); opacity:1; transition:opacity 0.4s ease;
                    max-width:90vw; text-align:center;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="toastError"
            style="position:fixed; top:80px; left:50%; transform:translateX(-50%); z-index:9999;
                    background:#1a1a2e; border:1px solid rgba(239,68,68,0.4);
                    color:#f87171; padding:10px 20px; border-radius:999px;
                    font-size:13px; font-weight:600; display:flex; align-items:center; gap:8px;
                    box-shadow:0 4px 20px rgba(239,68,68,0.15); opacity:1; transition:opacity 0.4s ease;
                    max-width:90vw; text-align:center;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- CONTENIDO -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('contenido')
    </main>

    <!-- FOOTER -->
    <footer class="mt-16 border-t border-gray-800" style="background:#0d0d1a">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-cyan-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-gamepad text-black text-sm"></i>
                    </div>
                    <span class="font-black text-cyan-400 text-xl neon-text">Fsociety</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Tu tienda de accesorios gamer en Perú. Mouses, teclados, audífonos y más al mejor precio.
                </p>
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center text-gray-500 hover:text-cyan-400 cursor-pointer transition">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </div>
                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center text-gray-500 hover:text-cyan-400 cursor-pointer transition">
                        <i class="fab fa-instagram text-xs"></i>
                    </div>
                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center text-gray-500 hover:text-cyan-400 cursor-pointer transition">
                        <i class="fab fa-tiktok text-xs"></i>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Categorías</h4>
                <div class="space-y-2">
                    @foreach(\App\Models\Categoria::all() as $cat)
                        <a href="{{ route('tienda.categoria', $cat->slug) }}"
                           class="block text-gray-500 hover:text-cyan-400 text-sm transition">
                            → {{ $cat->nombre }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Mi Cuenta</h4>
                <div class="space-y-2">
                @auth
                    @if(Auth::user()->rol !== 'administrador')
                        <a href="{{ route('pedidos.index') }}" class="block text-gray-500 hover:text-cyan-400 text-sm transition">→ Mis pedidos</a>
                        <a href="{{ route('carrito.index') }}" class="block text-gray-500 hover:text-cyan-400 text-sm transition">→ Mi carrito</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-500 hover:text-cyan-400 text-sm transition">→ Panel Admin</a>
                    @endif
                @else
                        <a href="{{ route('login') }}" class="block text-gray-500 hover:text-cyan-400 text-sm transition">→ Iniciar sesión</a>
                        <a href="{{ route('registro') }}" class="block text-gray-500 hover:text-cyan-400 text-sm transition">→ Registrarse</a>
                    @endauth
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Información</h4>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-shipping-fast text-cyan-500 w-4"></i> Envíos a todo el Perú
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-headset text-cyan-500 w-4"></i> Soporte 24/7
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-shield-alt text-cyan-500 w-4"></i> Compra 100% segura
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <i class="fas fa-undo text-cyan-500 w-4"></i> Devoluciones fáciles
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 py-4">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-center items-center gap-2">
                <p class="text-gray-600 text-xs">© {{ date('Y') }} Fsociety — Todos los derechos reservados</p>
            </div>
        </div>
    </footer>

    <script>
    // ── Dropdown usuario ──────────────────────────────────────────
    function toggleUser() {
        document.getElementById('userMenu').classList.toggle('hidden');
    }
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            document.getElementById('userMenu').classList.add('hidden');
        }
    });

    // ── Toast (añadir al carrito) ─────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        ['toast', 'toastError'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(function() {
                    el.style.opacity = '0';
                    el.style.transition = 'opacity 0.5s ease';
                    setTimeout(function() { el.remove(); }, 500);
                }, 1500);
            }
        });
    });
    </script>

</body>
</html>