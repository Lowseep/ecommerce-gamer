@extends('layouts.admin')

@section('titulo', 'Editar Producto')

@section('contenido')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.productos.index') }}"
           class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition flex-shrink-0">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-white font-bold text-lg">Editar Producto</h2>
            <p class="text-gray-500 text-xs">Modifica los campos que deseas actualizar</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-900 bg-opacity-40 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-6 text-sm">
            <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-2"></i>Corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-0.5 text-red-400">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.productos.actualizar', $producto->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Sección 1: Info básica -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6 mb-4">
            <h3 class="text-white font-semibold text-sm mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-cyan-500 rounded-lg flex items-center justify-center text-black text-xs font-bold flex-shrink-0">1</span>
                Información básica
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        Nombre del producto <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nombre" id="inputNombre"
                           value="{{ old('nombre', $producto->nombre) }}"
                           placeholder="Ej: Logitech G502 X Plus"
                           required
                           class="w-full bg-gray-800 border border-gray-700 hover:border-gray-600 focus:border-cyan-500 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none transition">
                    <p id="msgNombre" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Mínimo 3 caracteres
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        Categoría <span class="text-red-400">*</span>
                    </label>
                    <select name="categoria_id" id="inputCategoria" required
                        class="w-full bg-gray-800 border border-gray-700 hover:border-gray-600 focus:border-cyan-500 rounded-xl px-4 py-3 text-white text-sm focus:outline-none transition">
                        <option value="">Seleccionar categoría...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <p id="msgCategoria" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Selecciona una categoría
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    Descripción <span class="text-red-400">*</span>
                </label>
                <textarea name="descripcion" id="inputDescripcion" rows="4" required
                          placeholder="Describe las características principales del producto..."
                          class="w-full bg-gray-800 border border-gray-700 hover:border-gray-600 focus:border-cyan-500 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none transition resize-none">{{ old('descripcion', $producto->descripcion) }}</textarea>
                <p id="msgDescripcion" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Mínimo 10 caracteres
                </p>
            </div>
        </div>

        <!-- Sección 2: Precios y stock -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6 mb-4">
            <h3 class="text-white font-semibold text-sm mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-cyan-500 rounded-lg flex items-center justify-center text-black text-xs font-bold flex-shrink-0">2</span>
                Precios y stock
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        Precio (S/) <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">S/</span>
                        <input type="text" name="precio" id="inputPrecio"
                               value="{{ old('precio', $producto->precio) }}"
                               placeholder="0.00"
                               class="w-full bg-gray-800 border border-gray-700 hover:border-gray-600 focus:border-cyan-500 rounded-xl pl-9 pr-4 py-3 text-white text-sm placeholder-gray-600 focus:outline-none transition">
                    </div>
                    <p id="msgPrecio" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Solo números. Ej: 120.00
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                        Stock disponible <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-cubes absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" name="stock" id="inputStock"
                               value="{{ old('stock', $producto->stock) }}"
                               placeholder="Ej: 10"
                               class="w-full bg-gray-800 border border-gray-700 hover:border-gray-600 focus:border-cyan-500 rounded-xl pl-9 pr-4 py-3 text-white text-sm focus:outline-none transition">
                    </div>
                    <p id="msgStock" class="text-xs mt-1 text-gray-600 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Solo números enteros. Ej: 10
                    </p>
                </div>
            </div>
        </div>

        <!-- Sección 3: Imagen -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 md:p-6 mb-6">
            <h3 class="text-white font-semibold text-sm mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-cyan-500 rounded-lg flex items-center justify-center text-black text-xs font-bold flex-shrink-0">3</span>
                Imagen del producto
            </h3>

            @if($producto->imagen)
                <div class="flex items-center gap-3 md:gap-4 mb-4 p-3 bg-gray-800 rounded-xl border border-gray-700">
                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                         class="w-14 h-14 md:w-16 md:h-16 object-cover rounded-lg border border-gray-600 flex-shrink-0">
                    <div class="min-w-0">
                        <p class="text-white text-sm font-medium">Imagen actual</p>
                        <p class="text-gray-500 text-xs mt-0.5">Sube una nueva imagen para reemplazarla</p>
                    </div>
                </div>
            @endif

            <div class="border-2 border-dashed border-gray-700 hover:border-cyan-600 rounded-xl p-4 md:p-6 text-center transition cursor-pointer"
                 onclick="document.getElementById('imagenInput').click()">
                <div id="previewContainer" class="hidden mb-3">
                    <img id="previewImg" src="" class="h-28 md:h-32 mx-auto rounded-lg object-cover">
                </div>
                <div id="uploadIcon">
                    <i class="fas fa-cloud-upload-alt text-gray-600 text-3xl mb-2"></i>
                    <p class="text-gray-400 text-sm font-medium">
                        {{ $producto->imagen ? 'Haz click para cambiar la imagen' : 'Haz click para subir una imagen' }}
                    </p>
                    <p class="text-gray-600 text-xs mt-1">JPG, PNG o WEBP — Máximo 2MB</p>
                </div>
                <input type="file" id="imagenInput" name="imagen" accept="image/*" class="hidden"
                       onchange="previewImagen(this)">
            </div>
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit"
                class="flex items-center justify-center gap-2 btn-gamer text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg shadow-cyan-900/30">
                <i class="fas fa-save"></i> Actualizar Producto
            </button>
            <a href="{{ route('admin.productos.index') }}"
               class="flex items-center justify-center gap-2 border border-gray-700 hover:border-gray-500 text-gray-400 hover:text-white px-6 py-3 rounded-xl text-sm font-medium transition">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<script>
// ── Preview imagen ────────────────────────────────────────────
function previewImagen(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
            document.getElementById('uploadIcon').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Helpers ───────────────────────────────────────────────────
function setMsg(msgId, tipo, texto) {
    const msg = document.getElementById(msgId);
    if (!msg) return;
    msg.className = 'text-xs mt-1 flex items-center gap-1';
    if (tipo === 'error') {
        msg.classList.add('text-red-400');
        msg.innerHTML = '<i class="fas fa-circle-xmark"></i> ' + texto;
    } else if (tipo === 'ok') {
        msg.classList.add('text-green-400');
        msg.innerHTML = '<i class="fas fa-circle-check"></i> ' + texto;
    } else {
        msg.classList.add('text-gray-600');
        msg.innerHTML = '<i class="fas fa-info-circle"></i> ' + texto;
    }
}

function marcarCampo(inputId, estado) {
    const input = document.getElementById(inputId);
    if (!input) return;
    input.classList.remove('border-red-500','border-green-500','border-gray-700');
    if (estado === 'error')   input.classList.add('border-red-500');
    else if (estado === 'ok') input.classList.add('border-green-500');
    else                      input.classList.add('border-gray-700');
}

// ── NOMBRE ────────────────────────────────────────────────────
document.getElementById('inputNombre').addEventListener('input', validarNombre);
function validarNombre() {
    const val = document.getElementById('inputNombre').value.trim();
    if (!val) { marcarCampo('inputNombre','default'); setMsg('msgNombre','hint','Mínimo 3 caracteres'); return false; }
    if (val.length < 3) { marcarCampo('inputNombre','error'); setMsg('msgNombre','error','El nombre debe tener al menos 3 caracteres'); return false; }
    if (val.length > 255) { marcarCampo('inputNombre','error'); setMsg('msgNombre','error','El nombre es demasiado largo'); return false; }
    marcarCampo('inputNombre','ok'); setMsg('msgNombre','ok','Nombre válido');
    return true;
}

// ── CATEGORÍA ─────────────────────────────────────────────────
document.getElementById('inputCategoria').addEventListener('change', validarCategoria);
function validarCategoria() {
    const val = document.getElementById('inputCategoria').value;
    if (!val) { marcarCampo('inputCategoria','error'); setMsg('msgCategoria','error','Selecciona una categoría'); return false; }
    marcarCampo('inputCategoria','ok'); setMsg('msgCategoria','ok','Categoría seleccionada');
    return true;
}

// ── DESCRIPCIÓN ───────────────────────────────────────────────
document.getElementById('inputDescripcion').addEventListener('input', validarDescripcion);
function validarDescripcion() {
    const val = document.getElementById('inputDescripcion').value.trim();
    if (!val) { marcarCampo('inputDescripcion','default'); setMsg('msgDescripcion','hint','Mínimo 10 caracteres'); return false; }
    if (val.length < 10) { marcarCampo('inputDescripcion','error'); setMsg('msgDescripcion','error','La descripción debe tener al menos 10 caracteres'); return false; }
    marcarCampo('inputDescripcion','ok'); setMsg('msgDescripcion','ok','Descripción válida');
    return true;
}

// ── PRECIO ────────────────────────────────────────────────────
document.getElementById('inputPrecio').addEventListener('keypress', function(e) {
    if (!/[0-9.]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
        e.preventDefault();
        setMsg('msgPrecio','error','Solo se permiten números. Ej: 120.00');
        marcarCampo('inputPrecio','error');
        setTimeout(() => {
            if (this.value !== '') validarPrecio();
            else { marcarCampo('inputPrecio','default'); setMsg('msgPrecio','hint','Solo números. Ej: 120.00'); }
        }, 1500);
    }
});

document.getElementById('inputPrecio').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9.]/g, '');
    const partes = this.value.split('.');
    if (partes.length > 2) this.value = partes[0] + '.' + partes.slice(1).join('');
    if (partes.length === 2 && partes[1].length > 2) this.value = partes[0] + '.' + partes[1].substring(0, 2);
    const partesActuales = this.value.split('.');
    if (partesActuales[0].length > 8) {
        partesActuales[0] = partesActuales[0].substring(0, 8);
        this.value = partesActuales.join('.');
    }
    validarPrecio();
});

function validarPrecio() {
    const val = parseFloat(document.getElementById('inputPrecio').value);
    if (isNaN(val) || document.getElementById('inputPrecio').value === '') {
        marcarCampo('inputPrecio','default');
        setMsg('msgPrecio','hint','Solo números.');
        return false;
    }
    if (val <= 0) { marcarCampo('inputPrecio','error'); setMsg('msgPrecio','error','El precio debe ser mayor a 0'); return false; }
    marcarCampo('inputPrecio','ok'); setMsg('msgPrecio','ok','Precio válido: S/ ' + val.toFixed(2));
    return true;
}

// ── STOCK ─────────────────────────────────────────────────────
document.getElementById('inputStock').addEventListener('keypress', function(e) {
    if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
        e.preventDefault();
        setMsg('msgStock','error','Solo se permiten números enteros');
        marcarCampo('inputStock','error');
        setTimeout(() => {
            if (this.value !== '') validarStock();
            else { marcarCampo('inputStock','default'); setMsg('msgStock','hint','Solo números enteros. Ej: 10'); }
        }, 1000);
    }
});

document.getElementById('inputStock').addEventListener('input', function() {
    const original = this.value;
    this.value = this.value.replace(/[^0-9]/g, '');
    if (original !== this.value) {
        setMsg('msgStock','error','Solo se permiten números enteros');
        marcarCampo('inputStock','error');
    } else {
        validarStock();
    }
});

function validarStock() {
    const val = document.getElementById('inputStock').value;
    if (val === '') { marcarCampo('inputStock','default'); setMsg('msgStock','hint','Solo números enteros. Ej: 10'); return false; }
    if (parseInt(val) < 0) { marcarCampo('inputStock','error'); setMsg('msgStock','error','El stock no puede ser negativo'); return false; }
    marcarCampo('inputStock','ok'); setMsg('msgStock','ok','Stock válido: ' + val + ' unidades');
    return true;
}

// ── SUBMIT ────────────────────────────────────────────────────
document.querySelector('form').addEventListener('submit', function(e) {
    const ok1 = validarNombre();
    const ok2 = validarCategoria();
    const ok3 = validarDescripcion();
    const ok4 = validarPrecio();
    const ok5 = validarStock();
    if (!ok1 || !ok2 || !ok3 || !ok4 || !ok5) {
        e.preventDefault();
        document.querySelector('.border-red-500')?.scrollIntoView({ behavior:'smooth', block:'center' });
    }
});
</script>

@endsection