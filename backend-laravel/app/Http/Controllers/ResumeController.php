<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Retorna o currículo pelo ID, trazendo junto as seções ordenadas.
     */
    public function show($id)
    {
        // Busca o currículo (Resume) pelo ID
        // O 'with' já carrega as seções (Sections) automaticamente
        // O 'findOrFail' retorna erro 404 se o ID não existir
        $resume = Resume::with(['sections' => function($query) {
            $query->orderBy('order_index');
        }])->findOrFail($id);

        return response()->json($resume);
    }

    public function update(Request $request, $id)
    {
        // 1. Acha o currículo
        $resume = Resume::findOrFail($id);

        // 2. Atualiza os dados básicos do currículo (Título, Cor, Fonte...)
        // O método 'only' pega apenas os campos permitidos para evitar hackers
        $resume->update($request->only(['title', 'primary_color', 'font_family']));

        // 3. Atualiza as Seções (O Pulo do Gato 🐈)
        // Como as seções vêm dentro de um array, precisamos percorrer uma por uma
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionData) {
                // Buscamos a seção pelo ID dela para garantir que estamos editando a certa
                $section = \App\Models\Section::find($sectionData['id']);
                
                if ($section) {
                    // Atualizamos o conteúdo (JSON) dela
                    $section->update(['content' => $sectionData['content']]);
                }
            }
        }

        return response()->json(['message' => 'Currículo salvo com sucesso!']);
    }
}