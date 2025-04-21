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
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth.custom'])->group(function () {
    // Ruta del menú
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de campañas (índice general)
    Route::get('/campañas', [CampañaController::class, 'index'])->name('campañas.index');
    Route::get('/campañas/{id}/todas', [CampañaController::class, 'todasCampañas'])->name('campañas.todas');
    Route::get('/campañas/digital/{id}', [CampañaController::class, 'showDigital'])->name('campañas.digital');

    // Rutas para Campañas Display
    Route::prefix('campañas/display')->group(function () {
        Route::get('/create', [CampañaController::class, 'createDisplay'])->name('campañas.display.create');
        Route::post('/', [CampañaController::class, 'storeDisplay'])->name('campañas.display.store');
        Route::get('/{id}', [CampañaController::class, 'showDisplay'])->name('campañas.display');
    });

    // Rutas para Campañas Branded Content
    Route::prefix('campañas/branded')->group(function () {
        Route::get('/create', [CampañaController::class, 'createBranded'])->name('campañas.branded.create');
        Route::post('/', [CampañaController::class, 'storeBranded'])->name('campañas.branded.store');
        Route::get('/{id}', [CampañaController::class, 'showBranded'])->name('campañas.branded');
    });

    // Rutas para Campañas Redes Sociales
    Route::prefix('campañas/redes')->group(function () {
        Route::get('/create', [CampañaController::class, 'createRedes'])->name('campañas.redes.create');
        Route::post('/', [CampañaController::class, 'storeRedes'])->name('campañas.redes.store');
        Route::get('/{id}', [CampañaController::class, 'showRedes'])->name('campañas.redes');
    });

    Route::get('/filtrar-campañas', [MenuController::class, 'filtrarCampañas'])->name('filtrar.campañas');
});



