<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dettagli', function () {
    return view('dettagli');
})->name('dettagli');

Route::get('/conferma', function () {
    return view('conferma');
})->name('conferma');

Route::get('/stampa-invito', function () {
    return view('stampa-invito');
})->name('stampa-invito');

Route::get('/zona-selfie', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        return redirect()->route('login');
    }
    return view('zona-selfie');
})->name('zona-selfie');

Route::get('/wedding-fight', function () {
    if (!Auth::check() || !Auth::user()->isAmici()) {
        return redirect()->route('login');
    }
    return view('wedding-fight');
})->name('wedding-fight');

Route::get('/zona-rossa', function () {
    if (!Auth::check() || !Auth::user()->isAmici()) {
        return redirect()->route('login');
    }
    return view('zona-rossa');
})->name('zona-rossa');


Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::get('/register', function () {
        return view('register');
    })->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/area-riservata', function () {
        return view('area-riservata');
    })->name('area-riservata');

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
