<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ATENÇÃO: O nome aqui deve ser 'sections'
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            
            // Chave estrangeira ligando ao currículo (resumes)
            $table->foreignId('resume_id')->constrained()->onDelete('cascade');
            
            $table->string('type'); 
            $table->string('title');
            
            // O campo JSON mágico
            $table->json('content')->nullable();
            
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};