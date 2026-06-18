<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoPedido extends Model
{
    protected $table = 'seguimiento_pedidos';

    protected $fillable = [
        'pedido_id',
        'estado',
        'descripcion',
    ];

    // Un seguimiento pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}