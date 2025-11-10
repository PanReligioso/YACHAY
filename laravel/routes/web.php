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
Route::get('/libros', function () {
    return view('includes.libros.index');
})->name('libros.index');

Route::get('/libros/subir', function () {
    return view('includes.libros.subir');
})->name('libros.subir');

Route::get('/libros/{id}', function($id) {
    return view('includes.libros.show', ['id' => $id]);
})->name('libros.show');

// --- RUTAS DE APUNTES ---
Route::get('/apuntes', function () {
    return view('includes.apuntes.index');
})->name('apuntes.index');

Route::get('/apuntes/subir', function () {
    return view('includes.apuntes.subir');
})->name('apuntes.subir');

Route::get('/apuntes/{id}', function($id) {
    return view('includes.apuntes.show', ['id' => $id]);
})->name('apuntes.show');

// --- RUTAS DE TUTORÍAS ---
Route::get('/tutorias', function () {
    return view('includes.tutorias.index');
})->name('tutorias.index');

Route::get('/tutorias/crear', function () {
    return view('includes.tutorias.crear');
})->name('tutorias.crear');

Route::get('/tutorias/{id}', function($id) {
    return view('includes.tutorias.show', ['id' => $id]);
})->name('tutorias.show');

// --- RUTAS DE COMEDORES ---
Route::get('/comedores', function () {
    return view('includes.comedores.index');
})->name('comedores.index');

Route::get('/comedores/{id}', function($id) {
    return view('includes.comedores.show', ['id' => $id]);
})->name('comedores.show');
