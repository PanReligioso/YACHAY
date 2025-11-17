<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PerfilController;

// ======================================================
// RUTAS DE AUTENTICACIÓN (LOGIN, REGISTRO, GOOGLE)
// ======================================================

// Login y Registro - Rutas públicas
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('registro'); // Alias para /register

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ======================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
// ======================================================
Route::middleware('auth')->group(function () {

    // Perfil de Usuario
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

});

// ======================================================
// RUTAS PÚBLICAS
// ======================================================

// Ruta principal
Route::get('/', function () {
    return view('index');
})->name('home');

// --- RUTAS DE LIBROS ---
use App\Http\Controllers\LibroController;

Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
Route::get('/libros/subir', [LibroController::class, 'create'])->name('libros.subir');
Route::post('/libros/subir', [LibroController::class, 'store']);
Route::get('/libros/{id}', [LibroController::class, 'show'])->name('libros.show');

// --- RUTAS DE APUNTES ---
use App\Http\Controllers\ApuntesController;

Route::get('/apuntes', [ApuntesController::class, 'index'])->name('apuntes.index');
Route::get('/apuntes/subir', [ApuntesController::class, 'create'])->name('apuntes.subir');
Route::post('/apuntes/subir', [ApuntesController::class, 'store']);
Route::get('/apuntes/{id}', [ApuntesController::class, 'show'])->name('apuntes.show');

// --- RUTAS DE TUTORÍAS ---
use App\Http\Controllers\TutoriasController;

Route::get('/tutorias', [TutoriasController::class, 'index'])->name('tutorias.index');
Route::get('/tutorias/crear', [TutoriasController::class, 'create'])->name('tutorias.crear');
Route::post('/tutorias/crear', [TutoriasController::class, 'store']);
Route::get('/tutorias/{id}', [TutoriasController::class, 'show'])->name('tutorias.show');

// --- RUTAS DE COMEDORES ---
use App\Http\Controllers\ComedoresController;

Route::get('/comedores', [ComedoresController::class, 'index'])->name('comedores.index');
Route::get('/comedores/crear', [ComedoresController::class, 'create'])->name('comedores.crear');
Route::post('/comedores/crear', [ComedoresController::class, 'store']);
Route::get('/comedores/{id}', [ComedoresController::class, 'show'])->name('comedores.show');
