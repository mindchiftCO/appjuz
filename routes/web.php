<?php

//use App\Http\Controllers\juzgadoController;
use App\Http\Controllers\autoController;
use App\Http\Controllers\dashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

    //Route::get('/juzgados', [juzgadoController::class, 'index'])->name('juzgados.index');
    //Route::post('/juzgados', [juzgadoController::class, 'store'])->name('juzgados.store');
    //Route::post('/juzgados/{id}', [juzgadoController::class, 'update'])->name('juzgados.update');
    //Route::delete('/juzgados/{id}', [juzgadoController::class, 'destroy'])->name('juzgados.destroy');

    Route::get('/autos/create', [autoController::class, 'create'])->name('autos.create');
    Route::post('/autos', [autoController::class, 'store'])->name('autos.store');
    Route::get('/autos', [autoController::class, 'index'])->name('autos.index');
    Route::get('/autos/{auto}', [autoController::class, 'show'])->name('autos.show');
    Route::get('/autos/{auto}/edit', [autoController::class, 'edit'])->name('autos.edit');
    Route::put('/autos/{auto}', [autoController::class, 'update'])->name('autos.update');
    Route::delete('/autos/{auto}', [autoController::class, 'destroy'])->name('autos.destroy');
    //Route::get('/descargar/{archivo}', [autoController::class, 'descargar'])->name('autos.descargar');
    Route::post('/autos/{auto}/generar-word', [autoController::class, 'generarWord'])->name('autos.generarWord');
});
