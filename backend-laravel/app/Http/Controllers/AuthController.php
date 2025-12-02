<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Resume;
use App\Models\Section;

class AuthController extends Controller
{
    // CADASTRAR NOVO USUÁRIO
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // --- AQUI ESTÁ A MÁGICA: CRIAR CURRÍCULO PADRÃO ---
        $resume = Resume::create([
            'user_id' => $user->id,
            'title' => 'Meu Primeiro Currículo',
            'template_id' => 'moderno-blue',
            'primary_color' => '#3b82f6',
        ]);

        // Cria a seção de dados pessoais já preenchida
        Section::create([
            'resume_id' => $resume->id,
            'type' => 'personal',
            'title' => 'Dados Pessoais',
            'order_index' => 1,
            'content' => [
                'full_name' => $user->name,
                'email' => $user->email,
                'headline' => '', 'phone' => '', 'city' => ''
            ]
        ]);
        // --------------------------------------------------

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Cadastro realizado!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // FAZER LOGIN
    public function login(Request $request)
    {
        // 1. Tenta autenticar com email e senha
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login inválido (verifique email ou senha)'
            ], 401);
        }

        // 2. Se deu certo, busca o usuário e gera o token
        $user = User::where('email', $request['email'])->firstOrFail();
        
        // Deleta tokens antigos para limpar a sessão (opcional, mas recomendado)
        $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // SAIR (LOGOUT)
    public function logout(Request $request)
    {
        // Revoga o token atual
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Deslogado com sucesso']);
    }
}