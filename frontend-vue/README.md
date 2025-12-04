📄 Gerador de Currículos (Laravel + Vue.js)

Este é um sistema web para criação, edição e exportação de currículos em PDF. O projeto utiliza Laravel no backend para API e geração de PDFs, e Vue.js 3 (Composition API + TypeScript) no frontend para a interface interativa.
🚀 Tecnologias Utilizadas

    Backend: PHP 8.x, Laravel 10/11.

    Frontend: Vue.js 3, Vite, TypeScript, Tailwind CSS, Pinia (State Management).

    PDF: barryvdh/laravel-dompdf (Wrapper para DomPDF).

    Banco de Dados: MySQL / MariaDB (ou PostgreSQL).

📋 Pré-requisitos

Antes de começar, certifique-se de ter instalado em sua máquina:

    PHP (versão 8.1 ou superior)

    Composer

    Node.js & NPM

    Um banco de dados (MySQL via XAMPP/Laragon ou PostgreSQL)

🔧 Instalação e Configuração

Siga os passos abaixo para configurar o ambiente de desenvolvimento.
1. Backend (Laravel)

Abra o terminal na pasta raiz do projeto:
Bash

# 1. Instale as dependências do PHP
composer install

# 2. Crie o arquivo de configuração de ambiente
cp .env.example .env

# 3. Gere a chave da aplicação
php artisan key:generate

Configuração do Banco de Dados: Abra o arquivo .env e configure as credenciais do seu banco de dados:
Ini, TOML

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=root
DB_PASSWORD=

Migrações: Crie as tabelas no banco de dados:
Bash

php artisan migrate

(Opcional) Se houver seeders configurados:
Bash

php artisan db:seed

2. Frontend (Vue.js)
Bash

# 1. Instale as dependências do Node
npm install

🏃‍♂️ Como Rodar o Projeto

Para o projeto funcionar, você precisa de dois terminais abertos simultaneamente:

Terminal 1 (Backend - Laravel):
Bash

php artisan serve

O servidor iniciará geralmente em http://127.0.0.1:8000

Terminal 2 (Frontend - Vite):
Bash

npm run dev

O frontend iniciará geralmente em http://localhost:5173

Acesse o link mostrado no Terminal 2 para usar a aplicação.
🧠 Estrutura Importante do Código

Se você for mexer no código, aqui estão os arquivos principais:
📂 Backend (Laravel)

    Rotas: routes/api.php (Endpoints da API) e routes/web.php (Rotas de download).

    Controller: app/Http/Controllers/ResumeController.php

        Gerencia o salvamento (update), seleção de template e geração do PDF (download).

    Templates de PDF: resources/views/pdfs/

        resume-moderno.blade.php: Layout com barra lateral azul.

        resume-classico.blade.php: Layout tradicional preto e branco.

📂 Frontend (Vue.js)

    Editor: src/components/ResumeEditor.vue

        Contém o formulário, lógica de salvamento (fetch) e botões de troca de template.

    Store: src/stores/resume.ts

        Gerencia o estado global do currículo (dados, loading, salvamento).

🐛 Solução de Problemas Comuns

1. Erro de CORS (Bloqueio de API) Se ao tentar salvar aparecer erro de conexão, verifique o arquivo config/cors.php no Laravel. Certifique-se de que supports_credentials está true e allowed_origins inclui a porta do seu Vue (ex: http://localhost:5173).

2. Imagens não aparecem no PDF O DomPDF exige caminhos absolutos do sistema (ex: C:\xampp\htdocs\...) em vez de URLs (http://...). O ResumeController e os arquivos .blade.php já possuem lógica para converter isso usando public_path().

3. Token de Autorização As requisições PUT/POST exigem que o usuário esteja logado. O Frontend busca o token no localStorage. Se der erro 401, faça logout e login novamente.
🤝 Contribuição

    Faça um Fork do projeto.

    Crie uma Branch para sua Feature (git checkout -b feature/NovaFeature).

    Faça o Commit (git commit -m 'Adicionando Nova Feature').

    Faça o Push (git push origin feature/NovaFeature).

    Abra um Pull Request.

Feito com 💻 e café.