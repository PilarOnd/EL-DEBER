<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampañaController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta principal (home)
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Ruta del menú
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas de campañas
    Route::get('/campañas', [CampañaController::class, 'index'])->name('campañas.index');
    Route::get('/campañas/{id}', [CampañaController::class, 'show'])->name('campañas.show');
    Route::get('/campañas/digital/{id}', [CampañaController::class, 'showDigital'])->name('campañas.digital');
    Route::get('/campañas/{id}/todas', [CampañaController::class, 'todasCampañas'])->name('campañas.todas');

    Route::get('/filtrar-campañas', [MenuController::class, 'filtrarCampañas'])->name('filtrar.campañas');
});



