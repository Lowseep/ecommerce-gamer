@extends('layouts.admin')

@section('titulo', 'Monitor del Sistema')

@section('contenido')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
    <h2 class="text-white font-bold text-base md:text-lg">
        <i class="fas fa-microchip text-cyan-400 mr-2"></i> Monitor del Sistema Operativo
    </h2>
    <div class="flex items-center gap-2 text-xs text-gray-400">
        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse inline-block flex-shrink-0"></span>
        Actualizando cada 5 segundos
    </div>
</div>

<!-- Tarjetas de métricas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <!-- CPU -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-gray-400 text-xs uppercase tracking-wide">CPU</p>
            <i class="fas fa-microchip text-cyan-400"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-white" id="cpu-uso">—</p>
        <p class="text-gray-500 text-xs mt-1" id="cpu-nucleos">— núcleos</p>
        <div class="mt-3 bg-gray-800 rounded-full h-2">
            <div id="cpu-barra" class="bg-cyan-400 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
    </div>

    <!-- RAM -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-gray-400 text-xs uppercase tracking-wide">Memoria RAM</p>
            <i class="fas fa-memory text-purple-400"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-white" id="ram-uso">—</p>
        <p class="text-gray-500 text-xs mt-1" id="ram-detalle">— MB usado</p>
        <div class="mt-3 bg-gray-800 rounded-full h-2">
            <div id="ram-barra" class="bg-purple-400 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
    </div>

    <!-- Disco -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-gray-400 text-xs uppercase tracking-wide">Disco</p>
            <i class="fas fa-hdd text-green-400"></i>
        </div>
        <p class="text-2xl md:text-3xl font-bold text-white" id="disco-uso">—</p>
        <p class="text-gray-500 text-xs mt-1" id="disco-detalle">— GB usado</p>
        <div class="mt-3 bg-gray-800 rounded-full h-2">
            <div id="disco-barra" class="bg-green-400 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
    </div>

    <!-- Uptime -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-gray-400 text-xs uppercase tracking-wide">Uptime</p>
            <i class="fas fa-clock text-yellow-400"></i>
        </div>
        <p class="text-xl md:text-2xl font-bold text-white" id="uptime">—</p>
        <p class="text-gray-500 text-xs mt-1">Tiempo activo del servidor</p>
    </div>
</div>

<!-- Procesos y Red -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

    <!-- Carga de procesos -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
            <i class="fas fa-tasks text-cyan-400 mr-2"></i> Carga del Sistema
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Carga 1 min</span>
                <span class="text-white font-mono" id="load-1">—</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Carga 5 min</span>
                <span class="text-white font-mono" id="load-5">—</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Carga 15 min</span>
                <span class="text-white font-mono" id="load-15">—</span>
            </div>
            <hr class="border-gray-800">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Procesos activos</span>
                <span class="text-cyan-400 font-mono font-bold" id="procs-activos">—</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Total procesos</span>
                <span class="text-white font-mono" id="procs-total">—</span>
            </div>
        </div>
    </div>

    <!-- Red -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
        <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
            <i class="fas fa-network-wired text-cyan-400 mr-2"></i> Red
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">
                    <i class="fas fa-arrow-down text-green-400 mr-1"></i> Recibido (RX)
                </span>
                <span class="text-white font-mono" id="red-rx">—</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">
                    <i class="fas fa-arrow-up text-blue-400 mr-1"></i> Enviado (TX)
                </span>
                <span class="text-white font-mono" id="red-tx">—</span>
            </div>
        </div>

        <!-- Info /proc -->
        <div class="mt-6 bg-gray-800 rounded-lg p-3 overflow-x-auto">
            <p class="text-xs text-gray-400 font-mono whitespace-nowrap">
                <span class="text-green-400"># Fuente de datos</span><br>
                /proc/stat → CPU<br>
                /proc/meminfo → RAM<br>
                /proc/loadavg → Procesos<br>
                /proc/uptime → Uptime<br>
                /proc/net/dev → Red
            </p>
        </div>
    </div>
</div>

<!-- Log en tiempo real -->
<div class="bg-gray-900 border border-gray-800 rounded-xl p-4 md:p-5">
    <h3 class="text-white font-semibold mb-4 text-sm md:text-base">
        <i class="fas fa-terminal text-cyan-400 mr-2"></i> Log en tiempo real
    </h3>
    <div id="log-container"
         class="bg-black rounded-lg p-3 md:p-4 font-mono text-xs text-green-400 h-40 overflow-y-auto overflow-x-auto space-y-1">
        <p class="text-gray-500">Iniciando monitor...</p>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function agregarLog(mensaje) {
    const container = document.getElementById('log-container');
    const linea = document.createElement('p');
    const hora = new Date().toLocaleTimeString('es-PE');
    linea.innerHTML = `<span class="text-gray-500">[${hora}]</span> ${mensaje}`;
    container.appendChild(linea);
    container.scrollTop = container.scrollHeight;

    // Mantener solo las últimas 20 líneas
    while (container.children.length > 20) {
        container.removeChild(container.firstChild);
    }
}

function colorBarra(porcentaje) {
    if (porcentaje >= 90) return 'bg-red-400';
    if (porcentaje >= 70) return 'bg-yellow-400';
    return null;
}

function actualizarDatos() {
    fetch('{{ route("admin.sistema.datos") }}', {
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {

        // CPU
        document.getElementById('cpu-uso').textContent      = data.cpu.uso + '%';
        document.getElementById('cpu-nucleos').textContent  = data.cpu.nucleos + ' núcleos';
        const cpuBarra = document.getElementById('cpu-barra');
        cpuBarra.style.width = data.cpu.uso + '%';
        cpuBarra.className   = 'h-2 rounded-full transition-all duration-500 ' + (colorBarra(data.cpu.uso) || 'bg-cyan-400');

        // RAM
        document.getElementById('ram-uso').textContent     = data.memoria.porcentaje + '%';
        document.getElementById('ram-detalle').textContent = data.memoria.usado + ' MB / ' + data.memoria.total + ' MB';
        const ramBarra = document.getElementById('ram-barra');
        ramBarra.style.width = data.memoria.porcentaje + '%';
        ramBarra.className   = 'h-2 rounded-full transition-all duration-500 ' + (colorBarra(data.memoria.porcentaje) || 'bg-purple-400');

        // Disco
        document.getElementById('disco-uso').textContent     = data.disco.porcentaje + '%';
        document.getElementById('disco-detalle').textContent = data.disco.usado + ' GB / ' + data.disco.total + ' GB';
        const discoBarra = document.getElementById('disco-barra');
        discoBarra.style.width = data.disco.porcentaje + '%';
        discoBarra.className   = 'h-2 rounded-full transition-all duration-500 ' + (colorBarra(data.disco.porcentaje) || 'bg-green-400');

        // Uptime
        document.getElementById('uptime').textContent = data.uptime.formato;

        // Procesos
        document.getElementById('load-1').textContent       = data.procesos.load_1;
        document.getElementById('load-5').textContent       = data.procesos.load_5;
        document.getElementById('load-15').textContent      = data.procesos.load_15;
        document.getElementById('procs-activos').textContent = data.procesos.activos;
        document.getElementById('procs-total').textContent  = data.procesos.total;

        // Red
        document.getElementById('red-rx').textContent = data.red.rx + ' MB';
        document.getElementById('red-tx').textContent = data.red.tx + ' MB';

        // Log
        agregarLog(
            `CPU: <span class="text-cyan-400">${data.cpu.uso}%</span> | ` +
            `RAM: <span class="text-purple-400">${data.memoria.porcentaje}%</span> | ` +
            `Disco: <span class="text-green-400">${data.disco.porcentaje}%</span> | ` +
            `Procesos: <span class="text-yellow-400">${data.procesos.activos}/${data.procesos.total}</span>`
        );
    })
    .catch(err => {
        agregarLog('<span class="text-red-400">Error al obtener datos del sistema</span>');
    });
}

// Ejecutar al cargar y luego cada 5 segundos
actualizarDatos();
setInterval(actualizarDatos, 5000);
</script>

@endsection