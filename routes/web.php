<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampañaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController;

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
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Ruta del menú
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de campañas
    Route::get('/campañas', [CampañaController::class, 'index'])->name('campañas.index');
    Route::get('/campañas/display/{id}', [CampañaController::class, 'showDisplay'])->name('campañas.display');
    Route::get('/campañas/branded/{id}', [CampañaController::class, 'showBranded'])->name('campañas.branded');
    Route::get('/campañas/redes/{id}', [CampañaController::class, 'showRedes'])->name('campañas.redes');
    Route::get('/campañas/digital/{id}', [CampañaController::class, 'showDigital'])->name('campañas.digital');
    Route::get('/campañas/{id}/todas', [CampañaController::class, 'todasCampañas'])->name('campañas.todas');

    // Rutas para crear campañas
    Route::get('/campañas/display/create', [CampañaController::class, 'createDisplay'])->name('campañas.display.create');
    Route::get('/campañas/branded/create', [CampañaController::class, 'createBranded'])->name('campañas.branded.create');
    Route::get('/campañas/redes/create', [CampañaController::class, 'createRedes'])->name('campañas.redes.create');

    // Rutas para almacenar campañas
    Route::post('/campañas/display/store', [CampañaController::class, 'storeDisplay'])->name('campañas.display.store');
    Route::post('/campañas/branded/store', [CampañaController::class, 'storeBranded'])->name('campañas.branded.store');
    Route::post('/campañas/redes/store', [CampañaController::class, 'storeRedes'])->name('campañas.redes.store');

    Route::get('/filtrar-campañas', [MenuController::class, 'filtrarCampañas'])->name('filtrar.campañas');
});



