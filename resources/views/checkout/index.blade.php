@extends('layouts.app')

@section('titulo', 'Finalizar Pedido')

@section('contenido')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('carrito.index') }}"
       class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left text-sm"></i>
    </a>
    <h2 class="text-lg md:text-xl font-bold text-white">Finalizar Pedido</h2>
</div>

<!-- Pasos -->
<div class="flex items-center gap-1 md:gap-2 mb-6 overflow-x-auto">
    <div class="flex items-center gap-1.5 md:gap-2 flex-shrink-0">
        <span class="w-6 h-6 rounded-full bg-cyan-500 text-black text-xs font-bold flex items-center justify-center flex-shrink-0">1</span>
        <span class="text-cyan-400 text-xs md:text-sm font-medium">Carrito</span>
    </div>
    <div class="w-4 md:flex-1 h-px bg-cyan-500 mx-1.5 md:mx-2 flex-shrink-0"></div>    
    <div class="flex items-center gap-1.5 md:gap-2 flex-shrink-0">
        <span class="w-6 h-6 rounded-full bg-cyan-500 text-black text-xs font-bold flex items-center justify-center flex-shrink-0">2</span>
        <span class="text-cyan-400 text-xs md:text-sm font-medium whitespace-nowrap">Envío y Pago</span>
    </div>
    <div class="w-4 md:flex-1 h-px bg-gray-700 mx-1.5 md:mx-2 flex-shrink-0"></div>    
    <div class="flex items-center gap-1.5 md:gap-2 flex-shrink-0">
        <span class="w-6 h-6 rounded-full bg-gray-700 text-gray-400 text-xs font-bold flex items-center justify-center flex-shrink-0">3</span>
        <span class="text-gray-500 text-xs md:text-sm whitespace-nowrap">Confirmación</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Formulario izquierda -->
    <div class="lg:col-span-2 space-y-4">

        @if($errors->any())
            <div class="bg-red-900 bg-opacity-30 border border-red-700 rounded-xl px-4 py-3 text-sm">
                <p class="text-red-400 font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Corrige los errores:</p>
                @foreach($errors->all() as $error)
                    <p class="text-red-300 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('checkout.procesar') }}" method="POST" id="formCheckout" novalidate>
            @csrf

            <!-- Datos del comprador -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5 mb-4">
                <h3 class="text-white font-semibold text-sm mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-cyan-400"></i> Datos del comprador
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-gray-800 rounded-xl px-4 py-3">
                        <p class="text-gray-500 text-xs mb-0.5">Nombre</p>
                        <p class="text-white text-sm font-medium truncate">{{ Auth::user()->nombre }}</p>
                    </div>
                    <div class="bg-gray-800 rounded-xl px-4 py-3">
                        <p class="text-gray-500 text-xs mb-0.5">Correo</p>
                        <p class="text-white text-sm font-medium truncate">{{ Auth::user()->correo }}</p>
                    </div>
                </div>
            </div>

            <!-- Dirección de envío -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5 mb-4">
                <h3 class="text-white font-semibold text-sm mb-4 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-cyan-400"></i> Dirección de envío
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Dirección completa <span class="text-red-400">*</span></label>
                        <input type="text" name="direccion_linea" id="direccionLinea"
                               placeholder="Av. Los Olivos 123, Dpto 4B"
                               value="{{ old('direccion_linea') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-cyan-500 transition">
                        <p id="msgDireccion" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> Calle, número, piso o departamento
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Departamento <span class="text-red-400">*</span></label>
                            <select name="departamento" id="selectDepartamento"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                                <option value="">Seleccionar...</option>
                                <option value="Lima">Lima</option>
                                <option value="Arequipa">Arequipa</option>
                                <option value="Cusco">Cusco</option>
                                <option value="La Libertad">La Libertad</option>
                                <option value="Piura">Piura</option>
                                <option value="Lambayeque">Lambayeque</option>
                                <option value="Junín">Junín</option>
                                <option value="Ica">Ica</option>
                                <option value="Áncash">Áncash</option>
                                <option value="Loreto">Loreto</option>
                                <option value="San Martín">San Martín</option>
                                <option value="Cajamarca">Cajamarca</option>
                                <option value="Puno">Puno</option>
                                <option value="Huánuco">Huánuco</option>
                                <option value="Ucayali">Ucayali</option>
                                <option value="Amazonas">Amazonas</option>
                                <option value="Apurímac">Apurímac</option>
                                <option value="Ayacucho">Ayacucho</option>
                                <option value="Huancavelica">Huancavelica</option>
                                <option value="Moquegua">Moquegua</option>
                                <option value="Madre de Dios">Madre de Dios</option>
                                <option value="Pasco">Pasco</option>
                                <option value="Tacna">Tacna</option>
                                <option value="Tumbes">Tumbes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Provincia <span class="text-red-400">*</span></label>
                            <select name="provincia" id="selectProvincia"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                                <option value="">Primero elige departamento</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="direccion" id="direccionFinal">
            </div>

            <!-- Método de pago -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5 mb-5">
                <h3 class="text-white font-semibold text-sm mb-1 flex items-center gap-2">
                    <i class="fas fa-credit-card text-cyan-400"></i> Método de pago
                </h3>
                <p class="text-gray-500 text-xs mb-4">Simulación de Tarjeta</p>

                <!-- Tarjeta visual -->
                <div id="cardVisual" class="relative mb-5 h-40 md:h-44 rounded-2xl overflow-hidden select-none"
                    style="background:linear-gradient(135deg,#1a1a3e,#0d2847,#0a1628); transition: background 0.3s ease;">

                    <div class="absolute inset-0 opacity-10"
                         style="background:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,0.03) 10px,rgba(255,255,255,0.03) 20px);"></div>
                    <div class="absolute top-4 left-4 right-4 md:left-5 md:right-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-cyan-400 text-xs font-semibold tracking-widest uppercase">Fsociety Pay</p>
                            </div>
                            <div id="cardBrand" class="text-white text-xs font-bold tracking-widest italic">
                                ----
                            </div>
                        </div>
                        <!-- Chip -->
                        <div class="mt-3 md:mt-4 w-10 h-7 rounded-md"
                             style="background:linear-gradient(135deg,#d4af37,#f5d06a);"></div>
                        <!-- Número -->
                        <p id="cardDisplay" class="text-white text-base md:text-lg font-mono tracking-widest mt-3 text-shadow">
                            **** **** **** ****
                        </p>
                        <div class="flex justify-between mt-2">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wider">Titular</p>
                                <p id="cardNameDisplay" class="text-white text-xs md:text-sm font-medium truncate max-w-[140px] md:max-w-none">{{ strtoupper(Auth::user()->nombre) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400 text-xs uppercase tracking-wider">Vence</p>
                                <p id="cardExpDisplay" class="text-white text-xs md:text-sm font-mono">MM/AA</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campos tarjeta -->
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5">Número de tarjeta <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" id="cardNumber" name="card_number"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19" autocomplete="off"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-cyan-500 transition font-mono">
                            <span id="cardTypeIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs md:text-lg"></span>
                        </div>
                        <p id="msgCard" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> 16 dígitos de tu tarjeta
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">Vencimiento <span class="text-red-400">*</span></label>
                            <div class="flex items-center gap-1.5 md:gap-2">
                                <input type="text" id="cardMes" placeholder="MM"
                                    maxlength="2" autocomplete="off"
                                    class="w-12 md:w-16 bg-gray-800 border border-gray-700 rounded-xl px-2 md:px-3 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-cyan-500 transition font-mono text-center">
                                <span class="text-gray-500 font-mono font-bold">/</span>
                                <input type="text" id="cardAnio" placeholder="AA"
                                    maxlength="2" autocomplete="off"
                                    class="w-12 md:w-16 bg-gray-800 border border-gray-700 rounded-xl px-2 md:px-3 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-cyan-500 transition font-mono text-center">
                            </div>
                            <input type="hidden" id="cardExp" name="card_exp">
                            <p id="msgExp" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> <span class="hidden sm:inline">Mes y año de vencimiento</span><span class="sm:hidden">Mes/Año</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5">CVV <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input type="password" id="cardCvv" name="card_cvv"
                                       placeholder="•••"
                                       maxlength="4" autocomplete="off"
                                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-cyan-500 transition font-mono">
                                <button type="button" onclick="toggleCvv()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300">
                                    <i id="eyeCvv" class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            <p id="msgCvv" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i> <span class="hidden sm:inline">3 o 4 dígitos al dorso</span><span class="sm:hidden">3-4 dígitos</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full btn-gamer text-white font-bold py-3.5 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fas fa-lock"></i>
                <span class="hidden sm:inline">Confirmar Pedido — S/ {{ number_format($total, 2) }}</span>
                <span class="sm:hidden">Pagar S/ {{ number_format($total, 2) }}</span>
            </button>
        </form>
    </div>

    <!-- Resumen derecha -->
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-5 h-fit lg:sticky lg:top-24">
        <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
            <i class="fas fa-receipt text-cyan-400"></i> Resumen del pedido
        </h3>

        <div class="space-y-3 mb-4">
            @foreach($carrito->detalles as $detalle)
                <div class="flex gap-3 items-center">
                    @if($detalle->producto->imagen)
                        <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                             class="w-12 h-12 object-cover rounded-lg border border-gray-700 flex-shrink-0">
                    @else
                        <div class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-gamepad text-gray-600 text-xs"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-medium truncate">{{ $detalle->producto->nombre }}</p>
                        <p class="text-gray-500 text-xs">x{{ $detalle->cantidad }} × S/ {{ number_format($detalle->precio_unitario, 2) }}</p>
                    </div>
                    <span class="text-white text-xs font-bold flex-shrink-0">
                        S/ {{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}
                    </span>
                </div>
            @endforeach
        </div>

        <hr class="border-gray-800 mb-3">

        <div class="space-y-2 text-sm mb-3">
            <div class="flex justify-between text-gray-400 text-xs">
                <span>Subtotal ({{ $carrito->detalles->sum('cantidad') }} items)</span>
                <span>S/ {{ number_format($total, 2) }}</span>
            </div>
            <div class="flex justify-between text-gray-400 text-xs">
                <span class="flex items-center gap-1">
                    <i class="fas fa-truck text-cyan-400"></i> Envío
                </span>
                <span class="text-green-400 font-medium">Por coordinar</span>
            </div>
        </div>

        <hr class="border-gray-800 mb-3">

        <div class="flex justify-between text-white font-bold">
            <span>Total a pagar</span>
            <span class="text-cyan-400">S/ {{ number_format($total, 2) }}</span>
        </div>

        <div class="mt-4 space-y-2 border-t border-gray-800 pt-4">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i class="fas fa-shield-alt text-green-400"></i> Compra protegida
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i class="fas fa-lock text-cyan-400"></i> Datos seguros y encriptados
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i class="fas fa-headset text-purple-400"></i> Soporte ante cualquier problema
            </div>
        </div>
    </div>
</div>

<script>
// ── Provincias ────────────────────────────────────────────────
const provincias = {
    'Lima': ['Lima - Cercado','Ate','Barranco','Breña','Carabayllo','Chaclacayo','Chorrillos','Cieneguilla','Comas','El Agustino','Independencia','Jesús María','La Molina','La Victoria','Lince','Los Olivos','Lurigancho','Lurín','Magdalena del Mar','Miraflores','Pachacámac','Pucusana','Pueblo Libre','Puente Piedra','Punta Hermosa','Punta Negra','Rímac','San Bartolo','San Borja','San Isidro','San Juan de Lurigancho','San Juan de Miraflores','San Luis','San Martín de Porres','San Miguel','Santa Anita','Santa María del Mar','Santa Rosa','Santiago de Surco','Surquillo','Villa El Salvador','Villa María del Triunfo'],
    'Callao': ['Callao','Bellavista','Carmen de La Legua Reynoso','La Perla','La Punta','Mi Perú','Ventanilla'],
    'Arequipa': ['Arequipa','Alto Selva Alegre','Cayma','Cerro Colorado','Characato','Chiguata','Jacobo Hunter','José Luis Bustamante y Rivero','La Joya','Mariano Melgar','Miraflores','Mollebaya','Paucarpata','Pocsi','Polobaya','Quequeña','Sabandía','Sachaca','San Juan de Siguas','San Juan de Tarucani','Santa Isabel de Siguas','Santa Rita de Siguas','Socabaya','Tiabaya','Uchumayo','Vitor','Yanahuara','Yarabamba','Yura'],
    'Cusco': ['Cusco','Ccorca','Poroy','San Jerónimo','San Sebastián','Santiago','Saylla','Wanchaq'],
    'La Libertad': ['Trujillo','El Porvenir','Florencia de Mora','Huanchaco','La Esperanza','Laredo','Moche','Poroto','Salaverry','Simbal','Victor Larco Herrera'],
    'Piura': ['Piura','Castilla','Catacaos','Cura Mori','El Tallán','La Arena','La Unión','Las Lomas','Tambo Grande','Veintiséis de Octubre'],
    'Lambayeque': ['Chiclayo','Chongoyape','Eten','Eten Puerto','José Leonardo Ortiz','La Victoria','Lagunas','Monsefu','Nueva Arica','Oyotun','Picsi','Pimentel','Reque','Santa Rosa','Saña','Cayalti','Patapo','Pomalca','Pucala','Tuman'],
    'Junín': ['Huancayo','Carhuacallanga','Chacapampa','Chicche','Chilca','Chongos Alto','Chupuro','Colca','Cullhuas','El Tambo','Huacrapuquio','Hualhuas','Huancan','Huasicancha','Huayucachi','Ingenio','Pariahuanca','Pilcomayo','Pucara','Quichuay','Quilcas','San Agustín','San Jerónimo de Tunán','Saño','Sapallanga','Sicaya','Santo Domingo de Acobamba','Viques'],
    'Ica': ['Ica','La Tinguiña','Los Aquijes','Ocucaje','Pachacútec','Parcona','Pueblo Nuevo','Salas','San José de Los Molinos','San Juan Bautista','Santiago','Subtanjalla','Tate','Yauca del Rosario'],
    'Áncash': ['Huaraz','Cochabamba','Colcabamba','Huanchay','Independencia','Jangas','La Libertad','Llanganuco','Pampas Grande','Pararín','Tarica','Taricá'],
    'Loreto': ['Iquitos','Alto Nanay','Fernando Lores','Indiana','Las Amazonas','Mazan','Napo','Punchana','Torres Causana','Belén','San Juan Bautista'],
    'San Martín': ['Moyobamba','Calzada','Habana','Jepelacio','Soritor','Yantalo'],
    'Cajamarca': ['Cajamarca','Asunción','Chetilla','Cospan','Encañada','Jesús','Llacanora','Los Baños del Inca','Magdalena','Namora','San Juan'],
    'Puno': ['Puno','Acora','Amantani','Atuncolla','Capachica','Chucuito','Coata','Huata','Mañazo','Paucarcolla','Pichacani','Platería','San Antonio','Tiquillaca','Vilque'],
    'Huánuco': ['Huánuco','Amarilis','Chinchao','Churubamba','Margos','Quisqui','San Francisco de Cayrán','San Pedro de Chaulán','Santa María del Valle','Yarumayo','Pillco Marca'],
    'Ucayali': ['Callería','Campoverde','Iparía','Masisea','Yarinacocha','Nueva Requena','Manantay'],
    'Tacna': ['Tacna','Alto de la Alianza','Calana','Ciudad Nueva','Inclán','Pachia','Palca','Pocollay','Sama','Coronel Gregorio Albarracín Lanchipa'],
    'Tumbes': ['Tumbes','Corrales','La Cruz','Pampas de Hospital','San Jacinto','San Juan de la Virgen'],
    'Amazonas': ['Chachapoyas','Asunción','Balsas','Cheto','Chiliquín','Cluquis','Granada','Huancas','La Jalca','Leimebamba','Levanto','Magdalena','Mariscal Castilla','Molinopampa','Montevideo','Olleros','Quinjalca','San Francisco de Daguas','San Isidro de Maino','Soloco','Sonche'],
    'Apurímac': ['Abancay','Chacoche','Circa','Curahuasi','Huanipaca','Lambrama','Pichirhua','San Pedro de Cachora','Tamburco'],
    'Ayacucho': ['Ayacucho','Acocro','Acos Vinchos','Carmen Alto','Chiara','Ocros','Pacaycasa','Quinua','San José de Ticllas','San Juan Bautista','Santiago de Pischa','Socos','Tambillo','Vinchos','Jesús Nazareno'],
    'Huancavelica': ['Huancavelica','Acobambilla','Acoria','Conayca','Cuenca','Huachocolpa','Huayllahuara','Izcuchaca','Laria','Manta','Mariscal Cáceres','Moya','Nuevo Occoro','Palca','Pilchaca','Vilca','Yauli','Ascensión','Huando'],
    'Moquegua': ['Moquegua','Carumas','Cuchumbaya','Mariscal Nieto','Samegua','San Cristóbal','Torata'],
    'Madre de Dios': ['Tambopata','Inambari','Las Piedras','Laberinto'],
    'Pasco': ['Chaupimarca','Huachón','Huariaca','Huayllay','Ninacaca','Pallanchacra','Paucartambo','San Francisco de Asís de Yarusyacán','Simon Bolívar','Ticlacayán','Tinyahuarco','Vicco','Yanacancha'],
};

// ── Cambio de departamento ────────────────────────────────────
document.getElementById('selectDepartamento').addEventListener('change', function() {
    const dep    = this.value;
    const select = document.getElementById('selectProvincia');
    select.innerHTML = '<option value="">Seleccionar distrito...</option>';
    if (dep && provincias[dep]) {
        provincias[dep].forEach(function(p) {
            const opt = document.createElement('option');
            opt.value = p; opt.textContent = p;
            select.appendChild(opt);
        });
        marcar('selectDepartamento', 'ok');
    }
});

document.getElementById('selectProvincia').addEventListener('change', function() {
    if (this.value) marcar('selectProvincia', 'ok');
});

// ── Tarjeta visual ────────────────────────────────────────────
document.getElementById('cardNumber').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 16).replace(/(.{4})/g, '$1 ').trim();
    const num = this.value.replace(/\s/g, '');
    let tipo = '', gradiente = 'linear-gradient(135deg,#1a1a3e,#0d2847,#0a1628)';
    if (/^4/.test(num))                       { tipo = 'VISA';       gradiente = 'linear-gradient(135deg,#1a237e,#283593,#1565c0)'; }
    else if (/^5[1-5]|^2[2-7]/.test(num))    { tipo = 'MASTERCARD'; gradiente = 'linear-gradient(135deg,#b71c1c,#880e4f,#4a148c)'; }
    else if (/^3[47]/.test(num))              { tipo = 'AMEX';       gradiente = 'linear-gradient(135deg,#1b5e20,#2e7d32,#388e3c)'; }
    else if (/^6011|^65|^64[4-9]/.test(num)) { tipo = 'DISCOVER';   gradiente = 'linear-gradient(135deg,#e65100,#bf360c,#8d1a00)'; }
    document.getElementById('cardVisual').style.background = gradiente;
    document.getElementById('cardTypeIcon').textContent = tipo;
    document.getElementById('cardBrand').textContent = tipo || '----';
    document.getElementById('cardDisplay').textContent = this.value || '**** **** **** ****';
    validarCard();
});

// ── Campo MM/AA con edición libre ─────────────────────────────
// ── Mes ───────────────────────────────────────────────────────
document.getElementById('cardMes').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 2);
    if (this.value.length === 1 && parseInt(this.value) > 1) {
        this.value = '0' + this.value;
    }
    if (this.value.length === 2) {
        if (parseInt(this.value) > 12) this.value = '12';
        if (parseInt(this.value) === 0) this.value = '01';
        document.getElementById('cardAnio').focus();
    }
    actualizarExp();
    validarExp();
});

document.getElementById('cardMes').addEventListener('blur', function() {
    if (this.value.length === 1) {
        this.value = '0' + this.value;
    }
    actualizarExp();
    validarExp();
});

// ── Año ───────────────────────────────────────────────────────
document.getElementById('cardAnio').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 2);
    actualizarExp();
    validarExp();
});

// ── Sincronizar campos oculto y display ──────────────────────
function actualizarExp() {
    const mes  = document.getElementById('cardMes').value;
    const anio = document.getElementById('cardAnio').value;
    const val  = mes && anio ? mes + '/' + anio : mes + (anio ? '/' + anio : '');
    document.getElementById('cardExp').value = val;
    document.getElementById('cardExpDisplay').textContent = val || 'MM/AA';
}

// ── CVV ───────────────────────────────────────────────────────
document.getElementById('cardCvv').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').substring(0, 4);
    validarCvv();
});

function toggleCvv() {
    const f = document.getElementById('cardCvv');
    const i = document.getElementById('eyeCvv');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye'); i.classList.toggle('fa-eye-slash');
}

// ── Helpers ───────────────────────────────────────────────────
function marcar(id, estado) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('border-red-500','border-green-500','border-gray-700','border-gray-600');
    if (estado === 'error')   el.classList.add('border-red-500');
    else if (estado === 'ok') el.classList.add('border-green-500');
    else                      el.classList.add('border-gray-700');
}

function setMsg(id, tipo, texto) {
    const el = document.getElementById(id);
    if (!el) return;
    el.className = 'text-xs mt-1 flex items-center gap-1';
    if (tipo === 'error')    { el.classList.add('text-red-400');   el.innerHTML = '<i class="fas fa-circle-xmark"></i> ' + texto; }
    else if (tipo === 'ok')  { el.classList.add('text-green-400'); el.innerHTML = '<i class="fas fa-circle-check"></i> ' + texto; }
    else                     { el.classList.add('text-gray-600');  el.innerHTML = '<i class="fas fa-info-circle"></i> ' + texto; }
}

// ── Validaciones ──────────────────────────────────────────────
function validarDireccion() {
    const val = document.getElementById('direccionLinea').value.trim();
    if (!val)          { marcar('direccionLinea','error'); setMsg('msgDireccion','error','La dirección es obligatoria'); return false; }
    if (val.length<10) { marcar('direccionLinea','error'); setMsg('msgDireccion','error','La dirección es muy corta'); return false; }
    marcar('direccionLinea','ok'); setMsg('msgDireccion','ok','Dirección válida');
    return true;
}

function validarCard() {
    const val = document.getElementById('cardNumber').value.replace(/\s/g,'');
    if (!val)          { marcar('cardNumber','error'); setMsg('msgCard','error','El número de tarjeta es obligatorio'); return false; }
    if (val.length<16) { marcar('cardNumber','error'); setMsg('msgCard','error','El número debe tener 16 dígitos'); return false; }
    marcar('cardNumber','ok'); setMsg('msgCard','ok','Número válido');
    return true;
}

function validarExp() {
    const mes  = document.getElementById('cardMes').value;
    const anio = document.getElementById('cardAnio').value;

    if (!mes || !anio) {
        marcar('cardMes', !mes ? 'error' : 'ok');
        marcar('cardAnio', !anio ? 'error' : 'ok');
        setMsg('msgExp', 'error', 'La fecha de vencimiento es obligatoria');
        return false;
    }
    if (mes.length < 2 || anio.length < 2) {
        setMsg('msgExp', 'error', 'Completa el mes (01-12) y el año (Ej: 26)');
        return false;
    }

    const mesNum  = parseInt(mes);
    const anioNum = parseInt('20' + anio);
    const ahora   = new Date();

    if (mesNum < 1 || mesNum > 12) {
        marcar('cardMes', 'error');
        setMsg('msgExp', 'error', 'Mes inválido (01-12)');
        return false;
    }
    if (anioNum < ahora.getFullYear() || (anioNum === ahora.getFullYear() && mesNum < ahora.getMonth() + 1)) {
        marcar('cardAnio', 'error');
        setMsg('msgExp', 'error', 'La tarjeta está vencida');
        return false;
    }

    marcar('cardMes', 'ok');
    marcar('cardAnio', 'ok');
    setMsg('msgExp', 'ok', 'Fecha válida');
    return true;
}
function validarCvv() {
    const val = document.getElementById('cardCvv').value;
    if (!val)        { marcar('cardCvv','error'); setMsg('msgCvv','error','El CVV es obligatorio'); return false; }
    if (val.length<3){ marcar('cardCvv','error'); setMsg('msgCvv','error','CVV inválido, mínimo 3 dígitos'); return false; }
    marcar('cardCvv','ok'); setMsg('msgCvv','ok','CVV válido');
    return true;
}

document.getElementById('direccionLinea').addEventListener('input', validarDireccion);

// ── Submit ────────────────────────────────────────────────────
document.getElementById('formCheckout').addEventListener('submit', function(e) {
    let hayError = false;

    if (!validarDireccion()) hayError = true;

    const dep = document.getElementById('selectDepartamento').value;
    if (!dep) { marcar('selectDepartamento','error'); hayError = true; }
    else       { marcar('selectDepartamento','ok'); }

    const prov = document.getElementById('selectProvincia').value;
    if (!prov) { marcar('selectProvincia','error'); hayError = true; }
    else        { marcar('selectProvincia','ok'); }

    if (!validarCard())  hayError = true;
    if (!validarExp())   hayError = true;
    if (!validarCvv())   hayError = true;

    if (hayError) {
        e.preventDefault();
        document.querySelector('.border-red-500')?.scrollIntoView({ behavior:'smooth', block:'center' });
        const form = document.getElementById('formCheckout');
        if (!document.getElementById('alertaGeneral')) {
            const alerta = document.createElement('div');
            alerta.id = 'alertaGeneral';
            alerta.className = 'bg-red-900 bg-opacity-30 border border-red-700 rounded-xl px-4 py-3 text-sm mb-4';
            alerta.innerHTML = '<p class="text-red-400 font-semibold flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Completa todos los campos obligatorios antes de continuar</p>';
            form.insertBefore(alerta, form.firstChild);
            setTimeout(() => { if (alerta.parentNode) alerta.remove(); }, 4000);
        }
        return;
    }

    document.getElementById('direccionFinal').value =
        document.getElementById('direccionLinea').value.trim() + ', ' + prov + ', ' + dep;
});
</script>
@endsection