<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'estado',
        'total',
        'direccion_envio',
    ];

    // Un pedido pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Un pedido tiene muchos items
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id');
    }

    // Un pedido tiene seguimiento
    public function seguimientos()
    {
        return $this->hasMany(SeguimientoPedido::class, 'pedido_id');
    }
}