<?php

use Illuminate\Support\Facades\Route;

// ─── AUTH ─────────────────────────────────────────────────────────────────────
Route::get('/login',     [App\Http\Controllers\Auth\LoginController::class,    'index'])->name('login');
Route::post('/login',    [App\Http\Controllers\Auth\LoginController::class,    'autenticar'])->name('login.post');
Route::get('/registro',  [App\Http\Controllers\Auth\RegistroController::class, 'index'])->name('registro');
Route::post('/registro', [App\Http\Controllers\Auth\RegistroController::class, 'registrar'])->name('registro.post');
Route::post('/logout',   [App\Http\Controllers\Auth\LoginController::class,    'cerrarSesion'])->name('logout');

// ─── TIENDA PÚBLICA (sin login) ───────────────────────────────────────────────
Route::get('/',                [App\Http\Controllers\TiendaController::class, 'index'])->name('tienda.index');
Route::get('/producto/{slug}', [App\Http\Controllers\TiendaController::class, 'show'])->name('tienda.producto');
Route::get('/categoria/{slug}',[App\Http\Controllers\TiendaController::class, 'categoria'])->name('tienda.categoria');

// ─── RUTAS DE CLIENTE (requieren login + rol cliente) ─────────────────────────
Route::middleware('cliente')->group(function () {

    // Carrito
    Route::get('/carrito',          [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar', [App\Http\Controllers\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/quitar',  [App\Http\Controllers\CarritoController::class, 'quitar'])->name('carrito.quitar');
    Route::post('/carrito/vaciar',  [App\Http\Controllers\CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // Checkout
    Route::get('/checkout',                       [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout',                      [App\Http\Controllers\CheckoutController::class, 'procesar'])->name('checkout.procesar');
    Route::get('/checkout/confirmacion/{codigo}', [App\Http\Controllers\CheckoutController::class, 'confirmacion'])->name('checkout.confirmacion');

    // Mis pedidos
    Route::get('/mis-pedidos',          [App\Http\Controllers\MisPedidosController::class, 'index'])->name('pedidos.index');
    Route::get('/mis-pedidos/{codigo}', [App\Http\Controllers\MisPedidosController::class, 'detalle'])->name('pedidos.detalle');
});

// ─── PANEL ADMIN (requiere rol administrador) ─────────────────────────────────
Route::prefix('admin')->middleware('admin')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

    // Productos
    Route::get('/productos',             [App\Http\Controllers\Admin\ProductoController::class, 'index'])->name('admin.productos.index');
    Route::get('/productos/crear',       [App\Http\Controllers\Admin\ProductoController::class, 'crear'])->name('admin.productos.crear');
    Route::post('/productos',            [App\Http\Controllers\Admin\ProductoController::class, 'guardar'])->name('admin.productos.guardar');
    Route::get('/productos/{id}/editar', [App\Http\Controllers\Admin\ProductoController::class, 'editar'])->name('admin.productos.editar');
    Route::put('/productos/{id}',        [App\Http\Controllers\Admin\ProductoController::class, 'actualizar'])->name('admin.productos.actualizar');
    Route::delete('/productos/{id}',     [App\Http\Controllers\Admin\ProductoController::class, 'eliminar'])->name('admin.productos.eliminar');

    // Pedidos
    Route::get('/pedidos',                  [App\Http\Controllers\Admin\PedidoController::class, 'index'])->name('admin.pedidos.index');
    Route::get('/pedidos/{codigo}',         [App\Http\Controllers\Admin\PedidoController::class, 'detalle'])->name('admin.pedidos.detalle');
    Route::post('/pedidos/{codigo}/estado', [App\Http\Controllers\Admin\PedidoController::class, 'cambiarEstado'])->name('admin.pedidos.estado');

    // Usuarios
    Route::get('/usuarios',           [App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/usuarios/{id}/rol', [App\Http\Controllers\Admin\UsuarioController::class, 'cambiarRol'])->name('admin.usuarios.rol');

    // Monitor SO
    Route::get('/sistema',       [App\Http\Controllers\Admin\SistemaController::class, 'index'])->name('admin.sistema.index');
    Route::get('/sistema/datos', [App\Http\Controllers\Admin\SistemaController::class, 'datos'])->name('admin.sistema.datos');
});
