<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCarrito extends Model
{
    protected $table = 'detalle_carrito';

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
    ];

    // Un detalle pertenece a un carrito
    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    // Un detalle apunta a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}