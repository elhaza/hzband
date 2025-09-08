<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('contacto', function () {return view('contact');})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
