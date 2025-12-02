<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;

/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS (Qualquer pessoa pode acessar)
|--------------------------------------------------------------------------
*/

// 1. Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 2. Download do PDF (Deixamos público para facilitar o botão de download)
Route::get('/resumes/{id}/download', [ResumeController::class, 'download']);

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (Exigem Login / Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Teste de usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD DE CURRÍCULOS
    // Estas linhas SÓ podem estar aqui dentro. Se estiverem lá fora, a segurança quebra.
    Route::get('/resumes/{id}', [ResumeController::class, 'show']);
    Route::put('/resumes/{id}', [ResumeController::class, 'update']);
    Route::delete('/sections/{id}', [ResumeController::class, 'destroySection']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Retorna a lista de currículos do usuário logado
    Route::get('/my-resumes', function (Request $request) {
        return $request->user()->resumes;
    });
});