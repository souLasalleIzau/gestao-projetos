<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;

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

// ROUTES STATIC AUTH
Route::name('auth.')
    ->prefix('autenticacao')
    ->middleware(['guest'])
    ->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

Route::name('dashboard.')
    ->prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', function () {
            $user = Auth::user();

            return view('dashboard', ['user' => $user]);
        })->name('index');
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });

// ROUTES COMPANY
Route::name('client.')
    ->prefix('cliente')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('visualizar/{client_id}', [ClientController::class, 'show'])->name('show');
        Route::get('cadastrar', [ClientController::class, 'create'])->name('create');
        Route::post('cadastrar', [ClientController::class, 'store'])->name('store');
        Route::get('editar/{client_id}', [ClientController::class, 'edit'])->name('edit');
        Route::post('atualizar/{client_id}', [ClientController::class, 'update'])->name('update');
        Route::post('deletar/{client_id}', [ClientController::class, 'destroy'])->name('destroy');
    });

Route::get('/', function () {
    return redirect()->route('auth.login');
});