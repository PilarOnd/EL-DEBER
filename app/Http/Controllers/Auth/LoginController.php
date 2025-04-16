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
            // Leer el archivo usuarios.json
            $jsonPath = base_path('usuarios.json');
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            
            // Buscar el usuario
            $usuarioData = collect($jsonData['usuarios'])->where('usuario', $request->usuario)->first();

            if ($usuarioData && $request->password === $usuarioData['password']) {
                // Almacenar en sesión
                Session::put('usuario', $usuarioData);
                
                // Redirigir a dashboard
                return redirect()->route('dashboard');
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
        Session::forget('usuario');
        return redirect()->route('login');
    }
}