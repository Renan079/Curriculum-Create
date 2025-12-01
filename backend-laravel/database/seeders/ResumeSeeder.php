<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resume;
use App\Models\Section;

class ResumeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria um Usuário Dono (se não existir)
        $user = User::firstOrCreate(
            ['email' => 'teste@exemplo.com'],
            [
                'name' => 'Renan Desenvolvedor',
                'password' => bcrypt('password'), // senha padrão
            ]
        );

        // 2. Cria o Currículo
        $resume = Resume::create([
            'user_id' => $user->id,
            'title' => 'Currículo Full Stack',
            'template_id' => 'moderno-blue',
            'primary_color' => '#3b82f6', // Um azul bonito do Tailwind
        ]);

        // 3. Cria a Seção: Dados Pessoais
        Section::create([
            'resume_id' => $resume->id,
            'type' => 'personal',
            'title' => 'Dados Pessoais',
            'order_index' => 1,
            'content' => [
                'full_name' => 'Renan Silva',
                'headline' => 'Desenvolvedor Laravel & Vue',
                'email' => 'renan@loja.com',
                'phone' => '(88) 99999-9999',
                'city' => 'Jamacaru, CE',
            ]
        ]);

        // 4. Cria a Seção: Experiência Profissional
        Section::create([
            'resume_id' => $resume->id,
            'type' => 'experience',
            'title' => 'Experiência Profissional',
            'order_index' => 2,
            'content' => [
                'role' => 'Dono de Loja & Dev',
                'company' => 'Moda Masculina Jamacaru',
                'date_start' => '2023-01',
                'date_end' => 'Atualmente',
                'description' => 'Gestão de loja e desenvolvimento do sistema de vendas.'
            ]
        ]);
    }
}