@extends('layouts.auth')

@section('titulo', 'Iniciar Sesión')

@section('contenido')
<div class="grid grid-cols-1 md:grid-cols-2 gap-0 min-h-screen">
    <!-- Columna Izquierda - Branding (oculta en móvil) -->
    <div class="hidden md:flex bg-gradient-to-br from-cyan-950/20 via-gray-950 to-gray-900 border-r border-cyan-500/30 p-8 md:p-12 flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 bg-cyan-400 rounded-2xl flex items-center justify-center text-black text-lg shadow-lg">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h1 class="text-3xl font-black text-cyan-400 glow-cyan">Fsociety</h1>
            </div>

            <h2 class="text-4xl font-black text-white mb-2 leading-tight">Bienvenido</h2>
            <h2 class="text-4xl font-black text-cyan-400 mb-6 leading-tight glow-cyan">de vuelta</h2>
            <p class="text-gray-300 text-base leading-relaxed mb-10">Ingresa a tu cuenta para ver tus pedidos y seguir explorando nuestra tienda.</p>

            <div class="space-y-5">
                <div class="flex gap-4 items-start">
                    <span class="w-9 h-9 bg-cyan-500/10 border border-cyan-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-cyan-400 text-sm"></i>
                    </span>
                    <div>
                        <p class="font-bold text-white text-base">Revisa tus pedidos</p>
                        <p class="text-gray-400 text-xs mt-0.5">Consulta el estado y seguimiento de cada compra</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <span class="w-9 h-9 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shopping-cart text-green-400 text-sm"></i>
                    </span>
                    <div>
                        <p class="font-bold text-white text-base">Tu carrito te espera</p>
                        <p class="text-gray-400 text-xs mt-0.5">Los productos que agregaste siguen guardados</p>
                    </div>
                </div>
                <div class="flex gap-4 items-start">
                    <span class="w-9 h-9 bg-purple-500/10 border border-purple-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-purple-400 text-sm"></i>
                    </span>
                    <div>
                        <p class="font-bold text-white text-base">Gestiona tus envíos</p>
                        <p class="text-gray-400 text-xs mt-0.5">Controla a dónde y cuándo llegan tus productos</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-gray-500 text-xs">
            <p>© {{ date('Y') }} Fsociety. Todos los derechos reservados.</p>
        </div>
    </div>

    <!-- Columna Derecha - Formulario -->
    <div class="bg-gray-900 px-6 py-10 md:p-12 flex items-center justify-center min-h-screen md:min-h-0">
        <div class="w-full max-w-sm">

            <!-- Logo visible solo en móvil (ya que se oculta el branding) -->
            <div class="flex md:hidden items-center justify-center gap-3 mb-8">
                <div class="w-10 h-10 bg-cyan-400 rounded-2xl flex items-center justify-center text-black text-base shadow-lg">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h1 class="text-2xl font-black text-cyan-400 glow-cyan">Fsociety</h1>
            </div>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-white mb-2">Iniciar Sesión</h3>
                <p class="text-gray-400 text-sm">Accede a tu cuenta para continuar</p>
            </div>

            @if($errors->any())
                <div class="bg-red-900/30 border border-red-700/50 rounded-lg px-4 py-3 mb-5">
                    <p class="text-red-400 text-xs font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-1"></i>Error en el acceso:</p>
                    @foreach($errors->all() as $error)
                        <p class="text-red-300 text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="formLogin" novalidate class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                        <i class="fas fa-envelope mr-2 text-cyan-400"></i>Correo electrónico
                    </label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo') }}"
                           placeholder="tu@gmail.com" maxlength="255" autocomplete="off"
                           class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none transition focus:border-cyan-500">
                    <p id="correoMsg" class="text-xs mt-1.5 text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Ingresa tu correo registrado
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                        <i class="fas fa-lock mr-2 text-cyan-400"></i>Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" name="contrasena" id="contrasena"
                               placeholder="Tu contraseña" maxlength="50"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm placeholder-gray-500 focus:outline-none transition focus:border-cyan-500 pr-10">
                        <button type="button" onclick="togglePass('contrasena','eyeLogin')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                            <i id="eyeLogin" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    <p id="passMsg" class="text-xs mt-1.5 text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Mínimo 6 caracteres
                    </p>
                </div>

                <button type="submit" class="w-full btn-gamer text-white font-semibold py-3 rounded-lg text-sm flex items-center justify-center gap-2 mt-6">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>
            </form>

            <p class="text-center text-gray-400 text-sm mt-5">
                ¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold">Regístrate aquí</a>
            </p>
        </div>
    </div>
</div>

<script>
const dominiosValidos = ['gmail.com','hotmail.com','outlook.com','yahoo.com','icloud.com','protonmail.com','yahoo.es','hotmail.es'];

function togglePass(id, iconId) {
    const f = document.getElementById(id);
    const i = document.getElementById(iconId);
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye');
    i.classList.toggle('fa-eye-slash');
}

function setMsg(msgId, tipo, texto) {
    const msg = document.getElementById(msgId);
    msg.className = 'text-xs mt-1.5 flex items-center gap-1';
    if (tipo === 'error') {
        msg.classList.add('text-red-400');
        msg.innerHTML = '<i class="fas fa-circle-xmark"></i> ' + texto;
    } else if (tipo === 'ok') {
        msg.classList.add('text-green-400');
        msg.innerHTML = '<i class="fas fa-circle-check"></i> ' + texto;
    } else {
        msg.classList.add('text-gray-500');
        msg.innerHTML = '<i class="fas fa-info-circle"></i> ' + texto;
    }
}

function marcarCampo(inputId, estado) {
    const input = document.getElementById(inputId);
    input.classList.remove('border-red-500', 'border-green-500', 'border-gray-600');
    if (estado === 'error')      input.classList.add('border-red-500');
    else if (estado === 'ok')    input.classList.add('border-green-500');
    else                         input.classList.add('border-gray-600');
}

// CORREO
document.getElementById('correo').addEventListener('input', validarCorreo);
function validarCorreo() {
    const val = document.getElementById('correo').value.trim();
    if (!val) {
        marcarCampo('correo', 'default');
        setMsg('correoMsg', 'hint', 'Ingresa tu correo registrado');
        return false;
    }
    const regex = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
    if (!regex.test(val)) {
        marcarCampo('correo', 'error');
        setMsg('correoMsg', 'error', 'Ejemplo válido: usuario@gmail.com');
        return false;
    }
    const dom = val.split('@')[1].toLowerCase();
    if (!dominiosValidos.includes(dom)) {
        marcarCampo('correo', 'error');
        setMsg('correoMsg', 'error', 'Usa Gmail, Hotmail, Outlook, Yahoo, iCloud o ProtonMail');
        return false;
    }
    marcarCampo('correo', 'ok');
    setMsg('correoMsg', 'ok', 'Correo válido');
    return true;
}

// CONTRASEÑA
document.getElementById('contrasena').addEventListener('input', validarPass);
function validarPass() {
    const val = document.getElementById('contrasena').value;
    if (val.length === 0) {
        marcarCampo('contrasena', 'default');
        setMsg('passMsg', 'hint', 'Mínimo 6 caracteres');
        return false;
    }
    if (val.length < 6) {
        marcarCampo('contrasena', 'error');
        setMsg('passMsg', 'error', 'La contraseña debe tener al menos 6 caracteres');
        return false;
    }
    marcarCampo('contrasena', 'ok');
    setMsg('passMsg', 'ok', 'Contraseña válida');
    return true;
}

// SUBMIT
document.getElementById('formLogin').addEventListener('submit', function(e) {
    const ok1 = validarCorreo();
    const ok2 = validarPass();
    if (!ok1 || !ok2) {
        e.preventDefault();
        const firstError = document.querySelector('.border-red-500');
        if (firstError) firstError.focus();
    }
});
</script>

@endsection