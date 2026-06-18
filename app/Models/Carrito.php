<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';

    protected $fillable = [
        'usuario_id',
    ];

    // Un carrito pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Un carrito tiene muchos items
    public function detalles()
    {
        return $this->hasMany(DetalleCarrito::class, 'carrito_id');
    }
}