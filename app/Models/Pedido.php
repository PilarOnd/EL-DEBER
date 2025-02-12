<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['nombre', 
    'linea_pedido_id', 
    'anunciante', 
    'segmento', 
    'fecha_inicio', 
    'fecha_finalizacion', 
    'presupuesto', 
    'objetivo_proyectado', 
    'objetivo_alcanzado', 
    'porcentaje_efectividad'];

    public function lineaPedido()
    {
        return $this->belongsTo(LineadePedido::class, 'linea_pedido_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'anunciante');
    }
}




