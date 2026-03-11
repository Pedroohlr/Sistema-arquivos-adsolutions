<?php

use App\Http\Controllers\Admin\ArquivosController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\HistoricoController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Cliente\AuthController as ClienteAuthController;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\DownloadController;
use Illuminate\Support\Facades\Route;

// Página inicial redireciona para login do cliente
Route::get('/', function () {
    return redirect()->route('cliente.login');
});

/*
|--------------------------------------------------------------------------
| ROTAS DO ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Autenticação do Admin
    Route::middleware('guest.admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Rotas protegidas do Admin
    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [ArquivosController::class, 'index'])->name('dashboard');

        // Gestão de Arquivos
        Route::prefix('arquivos')->name('arquivos.')->group(function () {
            Route::get('/', [ArquivosController::class, 'index'])->name('index');

            // Grupos
            Route::post('grupos', [ArquivosController::class, 'storeGrupo'])->name('grupos.store');
            Route::get('grupos/{grupo}', [ArquivosController::class, 'showGrupo'])->name('grupo');
            Route::put('grupos/{grupo}', [ArquivosController::class, 'updateGrupo'])->name('grupos.update');
            Route::delete('grupos/{grupo}', [ArquivosController::class, 'destroyGrupo'])->name('grupos.destroy');

            // Subpastas
            Route::post('grupos/{grupo}/subpastas', [ArquivosController::class, 'storeSubpasta'])->name('subpastas.store');
            Route::put('subpastas/{subpasta}', [ArquivosController::class, 'updateSubpasta'])->name('subpastas.update');
            Route::delete('subpastas/{subpasta}', [ArquivosController::class, 'destroySubpasta'])->name('subpastas.destroy');

            // Arquivos
            Route::post('upload', [ArquivosController::class, 'uploadArquivo'])->name('upload');
            Route::put('arquivos/{arquivo}/mover', [ArquivosController::class, 'moverArquivo'])->name('arquivos.mover');
            Route::delete('arquivos/{arquivo}', [ArquivosController::class, 'destroyArquivo'])->name('arquivos.destroy');
        });

        // Gestão de Usuários
        Route::prefix('usuarios')->name('usuarios.')->group(function () {
            Route::get('/', [UsuariosController::class, 'index'])->name('index');
            Route::post('/', [UsuariosController::class, 'store'])->name('store');
            Route::put('{usuario}', [UsuariosController::class, 'update'])->name('update');
            Route::delete('{usuario}', [UsuariosController::class, 'destroy'])->name('destroy');
            Route::get('search', [UsuariosController::class, 'search'])->name('search');
        });

        // Gestão de clientes em subpastas
        Route::prefix('arquivos')->name('arquivos.')->group(function () {
            Route::post('subpastas/{subpasta}/clientes', [ArquivosController::class, 'adicionarClienteSubpasta'])->name('subpastas.clientes.add');
            Route::post('subpastas/{subpasta}/clientes/novo', [ArquivosController::class, 'criarClienteNaSubpasta'])->name('subpastas.clientes.create');
            Route::delete('subpastas/{subpasta}/clientes/{cliente}', [ArquivosController::class, 'removerClienteSubpasta'])->name('subpastas.clientes.remove');
        });

        // Histórico de Downloads
        Route::prefix('historico')->name('historico.')->group(function () {
            Route::get('/', [HistoricoController::class, 'index'])->name('index');
            Route::get('export', [HistoricoController::class, 'export'])->name('export');
        });
    });
});

/*
|--------------------------------------------------------------------------
| ROTAS DO CLIENTE
|--------------------------------------------------------------------------
*/

Route::prefix('cliente')->name('cliente.')->group(function () {
    // Autenticação do Cliente
    Route::middleware('guest.cliente')->group(function () {
        Route::get('login', [ClienteAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [ClienteAuthController::class, 'login'])->name('login.post');
    });

    // Rotas protegidas do Cliente
    Route::middleware('cliente')->group(function () {
        Route::post('logout', [ClienteAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('grupos/{grupo}', [DashboardController::class, 'showGrupo'])->name('grupos.show');

        // Downloads
        Route::get('download/{arquivo}', [DownloadController::class, 'download'])->name('download');
        Route::get('historico', [DownloadController::class, 'historico'])->name('historico');
    });
});
