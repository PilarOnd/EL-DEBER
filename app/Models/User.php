<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'usuario',
        'nombre',
        'rol'
    ];

    // Desactivar completamente la conexión a base de datos
    protected $connection = 'null';
    public $timestamps = false;
    protected $table = null;

    // Métodos necesarios para la autenticación
    public function getAuthIdentifier()
    {
        return $this->usuario;
    }

    public function getAuthIdentifierName()
    {
        return 'usuario';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    // Método para evitar consultas a la base de datos
    public function save(array $options = [])
    {
        return true;
    }
}