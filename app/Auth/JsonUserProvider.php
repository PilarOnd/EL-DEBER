<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use App\Models\User;

class JsonUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $jsonPath = base_path('usuarios.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        
        $usuarioData = collect($jsonData['usuarios'])->where('usuario', $identifier)->first();
        
        if ($usuarioData) {
            $user = new User();
            $user->usuario = $usuarioData['usuario'];
            $user->nombre = $usuarioData['nombre'];
            $user->rol = $usuarioData['rol'];
            $user->password = $usuarioData['password'];
            return $user;
        }
        
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        return;
    }

    public function retrieveByCredentials(array $credentials)
    {
        $jsonPath = base_path('usuarios.json');
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        
        $usuarioData = collect($jsonData['usuarios'])->where('usuario', $credentials['usuario'])->first();
        
        if ($usuarioData) {
            $user = new User();
            $user->usuario = $usuarioData['usuario'];
            $user->nombre = $usuarioData['nombre'];
            $user->rol = $usuarioData['rol'];
            $user->password = $usuarioData['password'];
            return $user;
        }
        
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->getAuthPassword() === $credentials['password'];
    }
} 