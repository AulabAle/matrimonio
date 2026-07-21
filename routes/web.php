<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Middleware\BlockSiteIfPdfViewer;

Route::middleware(BlockSiteIfPdfViewer::class)->group(function () {
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
});

Route::get('/shared/invito-matrimonio-monica-erasmo.pdf', function () {
    session(['block_site' => true]);
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invito')
        ->setOption('isRemoteEnabled', true);
    return $pdf->stream('invito-monica-erasmo.pdf');
})->name('invito-pdf');

Route::middleware(['guest', BlockSiteIfPdfViewer::class])->group(function () {
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
