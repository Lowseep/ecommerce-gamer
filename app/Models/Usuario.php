<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $authPasswordName = 'contrasena';

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed',
        ];
    }

    // Un usuario tiene un carrito
    public function carrito()
    {
        return $this->hasOne(Carrito::class, 'usuario_id');
    }

    // Un usuario tiene muchos pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }
}