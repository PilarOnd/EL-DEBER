<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creatividad extends Model
{
    protected $fillable = ['nombre', 
    'formato', 
    'espacio_cpm', 
    'anunciante'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'anunciante');
    }
}


