<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CampañaController;


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

Route::get('/', function () {
    return view('home');
});
Route::get('login', function () {
    return view('login');
});
Route::get('/dashboard', function () {
    return view('dashboard'); // Asegúrate de tener 'resources/views/dashboard.blade.php'
})->name('dashboard');

use App\Http\Controllers\Auth\LoginController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);



Route::get('/campañas', [CampañaController::class, 'index'])->name('campañas.index');
Route::get('/campañas/{id}', [CampañaController::class, 'show'])->name('campañas.show');




