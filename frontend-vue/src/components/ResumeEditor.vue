<script setup lang="ts">
import { onMounted } from 'vue';
import useResumeStore from '../stores/resume'; // Importação padrão

const store = useResumeStore();

onMounted(() => {
    if (!store.resume) {
        store.fetchUserResume();
    }
});

const downloadPdf = async () => {
    // 1. Primeiro, pedimos para a Store salvar
    // O 'await' garante que o código só continua DEPOIS que o salvamento terminar
    await store.saveResume();

    // 2. Se o salvamento deu certo (não estamos mais salvando), iniciamos o download
    if (!store.saving) {
        // Montamos a URL do PDF
        const url = `http://127.0.0.1:8000/api/resumes/${store.resume.id}/download`;
        
        // Abrimos o link em uma nova aba para iniciar o download
        window.open(url, '_blank');
    }
};
</script>

<template>
  <div v-if="store.loading" class="flex items-center justify-center h-screen bg-gray-100">
    <div class="text-xl text-indigo-600 font-semibold animate-pulse">
      Carregando...
    </div>
  </div>

  <div v-else-if="store.resume" class="flex h-screen w-full overflow-hidden bg-white font-sans text-left">
    
    <div class="w-1/2 h-full bg-gray-50 border-r border-gray-300 overflow-y-auto p-8">
      
      <header class="mb-8 flex justify-between items-center sticky top-0 bg-gray-50 z-10 py-4 border-b gap-2">
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-800">Editar Currículo</h2>
        </div>
        
        <button 
            @click="downloadPdf()"
            :disabled="store.saving"
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span v-if="store.saving">⏳ Processando...</span>
            <span v-else>📄 Baixar PDF</span>
        </button>

        <button 
            @click="store.saveResume()"
            :disabled="store.saving"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow flex items-center gap-2 transition disabled:opacity-50"
        >
            {{ store.saving ? '💾 Salvando...' : '💾 Salvar' }}
        </button>

        <button 
            @click="store.closeEditor()"
            class="text-gray-500 hover:text-indigo-600 text-sm font-bold px-3 flex items-center gap-1"
        >
            ⬅ Voltar
        </button>
        
      </header>

      <div v-for="section in store.resume.sections" :key="section.id || section.order_index" class="mb-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="font-bold text-lg text-indigo-600 flex items-center gap-2">
                📝 {{ section.title }}
            </h3>
            <button v-if="section.type !== 'personal'" @click="store.removeSection(section)" class="text-red-500 hover:text-red-700 text-sm font-semibold hover:bg-red-50 p-1 rounded transition">
                🗑️ Remover
            </button>
        </div>

        <div v-if="section.type === 'personal'" class="grid grid-cols-1 gap-4">
            <div><label class="label-padrao">Nome Completo</label><input v-model="section.content.full_name" type="text" class="input-padrao"></div>
            <div><label class="label-padrao">Título</label><input v-model="section.content.headline" type="text" class="input-padrao"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label-padrao">E-mail</label><input v-model="section.content.email" type="text" class="input-padrao"></div>
                <div><label class="label-padrao">Telefone</label><input v-model="section.content.phone" type="text" class="input-padrao"></div>
            </div>
            <div><label class="label-padrao">Cidade</label><input v-model="section.content.city" type="text" class="input-padrao"></div>
        </div>

        <div v-if="section.type === 'experience'" class="grid grid-cols-1 gap-4">
            <div><label class="label-padrao">Empresa</label><input v-model="section.content.company" type="text" class="input-padrao"></div>
            <div><label class="label-padrao">Cargo</label><input v-model="section.content.role" type="text" class="input-padrao"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label-padrao">Início</label><input v-model="section.content.date_start" type="text" class="input-padrao"></div>
                <div><label class="label-padrao">Fim</label><input v-model="section.content.date_end" type="text" class="input-padrao"></div>
            </div>
            <div><label class="label-padrao">Descrição</label><textarea v-model="section.content.description" rows="4" class="input-padrao"></textarea></div>
        </div>
      </div>

      <div class="mt-8 text-center pb-10">
        <button @click="store.addSection('experience')" class="bg-white border-2 border-indigo-600 text-indigo-600 font-bold py-2 px-6 rounded-full hover:bg-indigo-50 transition w-full">
            + Adicionar Experiência
        </button>
      </div>
    </div>

    <div class="w-1/2 h-full bg-gray-700 p-8 overflow-y-auto flex justify-center items-start">
        <div class="bg-white shadow-2xl p-10 min-h-[29.7cm] w-[21cm] transform scale-90 origin-top text-left" :style="{ fontFamily: store.resume.font_family }">
            
            <div v-for="section in store.resume.sections" :key="section.id || section.order_index" class="mb-6">
                <div v-if="section.type === 'personal'" class="text-center border-b-2 border-gray-200 pb-6 mb-6">
                    <h1 class="text-4xl font-bold uppercase" :style="{ color: store.resume.primary_color }">{{ section.content.full_name }}</h1>
                    <p class="text-xl text-gray-600 mt-2">{{ section.content.headline }}</p>
                    <div class="mt-4 text-sm text-gray-500 flex justify-center gap-3">
                        <span>📧 {{ section.content.email }}</span> | <span>📱 {{ section.content.phone }}</span> | <span>📍 {{ section.content.city }}</span>
                    </div>
                </div>

                <div v-if="section.type === 'experience'">
                    <h2 class="text-lg font-bold uppercase mb-4 border-b-2 pb-1" :style="{ color: store.resume.primary_color, borderColor: store.resume.primary_color }">{{ section.title }}</h2>
                    <div class="mb-4">
                        <div class="flex justify-between items-baseline"><h3 class="font-bold text-gray-800 text-lg">{{ section.content.role }}</h3><span class="text-sm font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ section.content.date_start }} - {{ section.content.date_end }}</span></div>
                        <div class="text-indigo-600 font-medium italic">{{ section.content.company }}</div>
                        <p class="text-gray-700 text-sm whitespace-pre-line mt-2">{{ section.content.description }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

  </div>

  <div v-else class="flex flex-col items-center ...">
      <h2 class="text-2xl font-bold text-red-600">Erro ao carregar</h2>
      <button @click="store.fetchUserResume()" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded">
        Tentar Novamente
      </button>
  </div>
</template>

<style scoped>
/* ESSENCIAIS PARA O ESTILO DOS INPUTS */
.input-padrao {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:border-indigo-500 focus:ring-indigo-500 transition;
}
.label-padrao {
    @apply block text-xs font-bold text-gray-500 uppercase;
}
</style>