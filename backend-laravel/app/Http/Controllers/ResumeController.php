<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{   

    // LISTAR: Retorna todos os currículos do usuário logado
    public function index(Request $request)
    {
        return $request->user()->resumes()->orderBy('created_at', 'desc')->get();
    }

    // CRIAR: Gera um novo currículo em branco
    public function store(Request $request)
    {
        $user = $request->user();

        // 1. Cria o currículo
        $resume = $user->resumes()->create([
            'title' => $request->title ?? 'Novo Currículo',
            'template_id' => 'moderno-blue',
            'primary_color' => '#3b82f6',
        ]);

        // 2. Adiciona a seção de dados pessoais (puxando do usuário)
        $resume->sections()->create([
            'type' => 'personal',
            'title' => 'Dados Pessoais',
            'order_index' => 1,
            'content' => [
                'full_name' => $user->name,
                'email' => $user->email,
                'headline' => '', 'phone' => '', 'city' => ''
            ]
        ]);

        return response()->json($resume);
    }

    // EXCLUIR: Apaga o currículo inteiro
    public function destroy(Request $request, $id)
    {
        // Garante que o currículo pertence ao usuário antes de apagar
        $resume = $request->user()->resumes()->findOrFail($id);
        
        $resume->delete();

        return response()->json(['message' => 'Currículo excluído']);
    }

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
        $resume = Resume::findOrFail($id);
        $resume->update($request->only(['title', 'primary_color', 'font_family']));

        if ($request->has('sections')) {
            foreach ($request->sections as $sectionData) {
                // LÓGICA INTELIGENTE:
                // Se tem 'id', atualiza. Se não tem, cria um novo.
                $resume->sections()->updateOrCreate(
                    ['id' => $sectionData['id'] ?? null], // Busca por ID
                    [
                        'type' => $sectionData['type'],
                        'title' => $sectionData['title'],
                        'content' => $sectionData['content'],
                        'order_index' => $sectionData['order_index'] ?? 0
                    ]
                );
            }
        }

        // Retorna o currículo atualizado (importante para pegar os IDs dos novos itens)
        return response()->json([
            'message' => 'Salvo com sucesso!',
            'resume' => $resume->load(['sections' => fn($q) => $q->orderBy('order_index')])
        ]);
    }

    /**
     * Apaga uma seção específica
     */
    public function destroySection($id)
    {
        $section = \App\Models\Section::findOrFail($id);
        $section->delete();
        return response()->json(['message' => 'Seção removida']);
    }


//    public function download($id)
//   {
//        $resume = Resume::with(['sections' => function($query) {
//           $query->orderBy('order_index');
//        }])->findOrFail($id);
//
//        $html = view('pdf.resume', compact('resume'))->render();
//
//        $pdf = Browsershot::html($html)
//            ->format('A4')
//            ->margins(10, 10, 10, 10)
//            ->showBackground()
//            ->noSandbox() // <--- ADICIONE ESTA LINHA AQUI!
 //           ->pdf();
//
//        return response()->streamDownload(function () use ($pdf) {
//            echo $pdf;
 //       }, 'curriculo.pdf', ['Content-Type' => 'application/pdf']);
//    }   

    public function download($id)
    {
        // Carrega o currículo com as seções ordenadas
        $resume = Resume::with(['sections' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->findOrFail($id);

        // REMOVA OU COMENTE ESTA LINHA:
        //  dd($resume->sections->toArray()); 

        $pdf = Pdf::loadView('pdfs.resume-moderno', ['resume' => $resume]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download("curriculo-{$id}.pdf");
    }

}