<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineadePedido extends Model
{
    protected $fillable = ['nombre',
    'estado', 
    'tipo', 
    'fecha_inicio', 
    'fecha_finalizacion', 
    'objetivo', 
    'empresa_id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'linea_pedido_id');
    }
}



