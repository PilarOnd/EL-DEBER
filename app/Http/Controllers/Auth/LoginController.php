<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);

        try {
            // Leer el archivo base.json
            $jsonPath = base_path('base.json');
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            
            // Buscar el usuario
            $usuarioData = collect($jsonData['usuarios'])->where('usuario', $request->usuario)->first();

            if ($usuarioData && $request->password === $usuarioData['password']) {
                // Crear instancia del modelo User
                $user = new User();
                $user->usuario = $usuarioData['usuario'];
                $user->nombre = $usuarioData['nombre'];
                $user->rol = $usuarioData['rol'];
                
                // Almacenar en sesión
                Session::put('user', $usuarioData);
                
                // Login manual sin usar base de datos
                Auth::login($user, true);

                // Redirigir a menu.blade.php
                return redirect()->route('menu');
            }

            return back()
                ->withErrors(['usuario' => 'Las credenciales no coinciden con nuestros registros.'])
                ->withInput($request->except('password'));

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()])
                ->withInput($request->except('password'));
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        return redirect()->route('login');
    }
}