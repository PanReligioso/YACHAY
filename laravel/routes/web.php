<?php

use Illuminate\Support\Facades\Route;

// Página principal
Route::view('/', 'index')->name('home');

// Autenticación
Route::view('/login', 'auth.login')->name('login');
Route::view('/registro', 'auth.register')->name('registro');

// Libros
Route::view('/libros', 'includes.libros.index')->name('libros.index');
Route::view('/libros/subir', 'includes.libros.subir')->name('libros.subir');
Route::get('/libros/{id}', function($id) {
    return view('includes.libros.show', ['id' => $id]);
})->name('libros.show');

// Apuntes (para después)
Route::view('/apuntes', 'includes.apuntes.index')->name('apuntes.index');

// Tutorías (para después)
Route::view('/tutorias', 'includes.tutorias.index')->name('tutorias.index');

// Comedores (para después)
Route::view('/comedores', 'includes.comedores.index')->name('comedores.index');
