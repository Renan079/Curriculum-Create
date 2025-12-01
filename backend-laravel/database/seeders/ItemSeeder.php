<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Apaga todos os itens existentes para evitar duplicatas em re-seed
        Item::truncate(); 

        // 2. Cria 3 itens de exemplo usando o Model
        Item::create([
            'nome' => 'Especificação do Coordenador',
            'descricao' => 'Documento base do projeto, aguardando o Wendel.',
        ]);

        Item::create([
            'nome' => 'Configuração do Ambiente',
            'descricao' => 'Configuração do XAMPP, Laravel e Vue/Tailwind.',
        ]);

        Item::create([
            'nome' => 'Implementação da API REST',
            'descricao' => 'Criação do primeiro endpoint /api/items.',
        ]);

        // Se você usar a classe DB:
        // DB::table('items')->insert([
        //     'nome' => 'Item 4',
        //     'descricao' => 'Mais um item de teste',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
