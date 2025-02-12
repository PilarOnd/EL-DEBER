<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');  // Asegúrate de que 'login' es el nombre de tu vista.
    }

    public function login(Request $request)
    {
        // Validar las credenciales
        $credentials = $request->only('usuario', 'password');

        if (Auth::attempt($credentials)) {
            // Si las credenciales son correctas, redirigir al dashboard o página principal
            return redirect()->intended('/home');
        }

        // Si las credenciales no son correctas
        return back()->withErrors([
            'usuario' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }
}
