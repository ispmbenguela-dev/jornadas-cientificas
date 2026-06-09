<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CertificadoController as AdminCertificadoController;
use App\Http\Controllers\Admin\ConfiguracaoController as AdminConfiguracaoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EdicaoController as AdminEdicaoController;
use App\Http\Controllers\Admin\InscricaoController as AdminInscricaoController;
use App\Http\Controllers\Admin\McoController as AdminMcoController;
use App\Http\Controllers\Admin\SubmissaoController as AdminSubmissaoController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\SubmissaoController;
use Illuminate\Support\Facades\Route;

/*
| Público
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programa', [HomeController::class, 'programa'])->name('programa');
Route::get('/edicoes', [HomeController::class, 'edicoes'])->name('edicoes.index');
Route::get('/edicoes/{slug}', [HomeController::class, 'edicao'])->name('edicoes.show');

Route::get('/inscricao', [InscricaoController::class, 'create'])->name('inscricao.create');
Route::post('/inscricao', [InscricaoController::class, 'store'])->name('inscricao.store');
Route::post('/inscricao/verificar-docente', [InscricaoController::class, 'verificarDocente'])->name('inscricao.verificar_docente');
Route::get('/inscricao/sucesso/{inscricao}', [InscricaoController::class, 'sucesso'])->name('inscricao.sucesso');

Route::get('/submissao', [SubmissaoController::class, 'create'])->name('submissao.create');
Route::post('/submissao', [SubmissaoController::class, 'store'])->name('submissao.store');
Route::get('/submissao/sucesso/{submissao}', [SubmissaoController::class, 'sucesso'])->name('submissao.sucesso');

Route::get('/certificado/{codigo}', [CertificadoController::class, 'verify'])->name('certificado.verify');
Route::get('/certificado/{codigo}/download', [CertificadoController::class, 'download'])->name('certificado.download');

/*
| Admin
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('edicoes', [AdminEdicaoController::class, 'index'])->name('edicoes.index');
        Route::get('edicoes/create', [AdminEdicaoController::class, 'create'])->name('edicoes.create');
        Route::post('edicoes', [AdminEdicaoController::class, 'store'])->name('edicoes.store');
        Route::get('edicoes/{edicao}', [AdminEdicaoController::class, 'show'])->name('edicoes.show');
        Route::get('edicoes/{edicao}/edit', [AdminEdicaoController::class, 'edit'])->name('edicoes.edit');
        Route::put('edicoes/{edicao}', [AdminEdicaoController::class, 'update'])->name('edicoes.update');
        Route::delete('edicoes/{edicao}', [AdminEdicaoController::class, 'destroy'])->name('edicoes.destroy');
        Route::post('edicoes/{edicao}/activar', [AdminEdicaoController::class, 'activar'])->name('edicoes.activar');
        Route::post('edicoes/{edicao}/mini-cursos', [AdminEdicaoController::class, 'miniCursoStore'])->name('edicoes.mini_curso.store');
        Route::put('edicoes/{edicao}/mini-cursos/{miniCurso}', [AdminEdicaoController::class, 'miniCursoUpdate'])->name('edicoes.mini_curso.update');
        Route::delete('edicoes/{edicao}/mini-cursos/{miniCurso}', [AdminEdicaoController::class, 'miniCursoDestroy'])->name('edicoes.mini_curso.destroy');

        Route::get('inscricoes', [AdminInscricaoController::class, 'index'])->name('inscricoes.index');
        Route::get('inscricoes/export', [AdminInscricaoController::class, 'export'])->name('inscricoes.export');
        Route::get('inscricoes/export-pdf', [AdminInscricaoController::class, 'exportPdf'])->name('inscricoes.export_pdf');
        Route::get('inscricoes/{inscricao}', [AdminInscricaoController::class, 'show'])->name('inscricoes.show');
        Route::get('inscricoes/{inscricao}/edit', [AdminInscricaoController::class, 'edit'])->name('inscricoes.edit');
        Route::put('inscricoes/{inscricao}', [AdminInscricaoController::class, 'update'])->name('inscricoes.update');
        Route::delete('inscricoes/{inscricao}', [AdminInscricaoController::class, 'destroy'])->name('inscricoes.destroy');

        Route::get('mco', [AdminMcoController::class, 'index'])->name('mco.index');
        Route::get('mco/create', [AdminMcoController::class, 'create'])->name('mco.create');
        Route::post('mco', [AdminMcoController::class, 'store'])->name('mco.store');
        Route::get('mco/{mco}/edit', [AdminMcoController::class, 'edit'])->name('mco.edit');
        Route::put('mco/{mco}', [AdminMcoController::class, 'update'])->name('mco.update');
        Route::delete('mco/{mco}', [AdminMcoController::class, 'destroy'])->name('mco.destroy');

        Route::get('configuracoes', [AdminConfiguracaoController::class, 'index'])->name('configuracoes.index');
        Route::post('configuracoes', [AdminConfiguracaoController::class, 'update'])->name('configuracoes.update');

        Route::get('certificados', [AdminCertificadoController::class, 'index'])->name('certificados.index');
        Route::get('certificados/create', [AdminCertificadoController::class, 'create'])->name('certificados.create');
        Route::post('certificados', [AdminCertificadoController::class, 'store'])->name('certificados.store');
        Route::post('certificados/gerar-lote', [AdminCertificadoController::class, 'gerarLote'])->name('certificados.gerar_lote');
        Route::post('certificados/gerar-prelectores', [AdminCertificadoController::class, 'gerarPrelectores'])->name('certificados.gerar_prelectores');
        Route::post('certificados/enviar-todos', [AdminCertificadoController::class, 'enviarTodos'])->name('certificados.enviar_todos');
        Route::get('certificados/{certificado}', [AdminCertificadoController::class, 'show'])->name('certificados.show');
        Route::get('certificados/{certificado}/preview', [AdminCertificadoController::class, 'preview'])->name('certificados.preview');
        Route::get('certificados/{certificado}/download', [AdminCertificadoController::class, 'download'])->name('certificados.download');
        Route::post('certificados/{certificado}/enviar', [AdminCertificadoController::class, 'enviar'])->name('certificados.enviar');
        Route::delete('certificados/{certificado}', [AdminCertificadoController::class, 'destroy'])->name('certificados.destroy');

        Route::get('submissoes', [AdminSubmissaoController::class, 'index'])->name('submissoes.index');
        Route::get('submissoes/export', [AdminSubmissaoController::class, 'export'])->name('submissoes.export');
        Route::get('submissoes/export-pdf', [AdminSubmissaoController::class, 'exportPdf'])->name('submissoes.export_pdf');
        Route::get('submissoes/{submissao}', [AdminSubmissaoController::class, 'show'])->name('submissoes.show');
        Route::get('submissoes/{submissao}/edit', [AdminSubmissaoController::class, 'edit'])->name('submissoes.edit');
        Route::put('submissoes/{submissao}', [AdminSubmissaoController::class, 'update'])->name('submissoes.update');
        Route::delete('submissoes/{submissao}', [AdminSubmissaoController::class, 'destroy'])->name('submissoes.destroy');
    });
});
