<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable =
    ['nombre', 
    'email',
    'tipo'];

    public function users()
    {
        return $this->hasMany(User::class, 'rol');  // Relación inversa con la tabla 'users'
    }

    public function lineasPedidos()
    {
        return $this->hasMany(LineadePedido::class, 'empresa_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'anunciante');
    }

    public function creatividades()
    {
        return $this->hasMany(Creatividad::class, 'anunciante');
    }
}
