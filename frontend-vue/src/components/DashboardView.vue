<script setup lang="ts">
import { onMounted } from 'vue';
import useResumeStore from '../stores/resume';

const store = useResumeStore();

// Ao abrir o dashboard, busca a lista atualizada
onMounted(() => {
    store.fetchResumes();
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 font-sans">
    
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <h1 class="text-xl font-bold text-indigo-600">Meus Currículos</h1>
          <button @click="store.logout()" class="text-gray-500 hover:text-red-600 text-sm font-medium">
            Sair
          </button>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      
      <div v-if="store.loading" class="text-center py-20">
        <div class="text-indigo-500 text-lg animate-pulse">Carregando seus documentos...</div>
      </div>

      <div v-else>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <button 
            @click="store.createResume()"
            class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition group cursor-pointer bg-white"
          >
            <div class="h-12 w-12 text-gray-400 group-hover:text-indigo-500 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </div>
            <span class="text-gray-500 font-medium group-hover:text-indigo-600">Criar Novo Currículo</span>
          </button>

          <div v-for="resume in store.resumes" :key="resume.id" class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden border border-gray-100 flex flex-col h-64">
            <div class="h-2/3 bg-gray-50 flex items-center justify-center border-b">
                <span class="text-4xl">📄</span>
            </div>
            
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div class="flex flex-col gap-2">
                    <h3 class="font-bold text-gray-800 truncate">{{ resume.title }}</h3>
                    <p class="text-xs text-gray-500">Atualizado recentemente</p>
                    <a 
                        :href="`http://127.0.0.1:8000/resumes/${resume.id}/download`" 
                        target="_blank"
                        class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700"
                    >
                        Baixar PDF
                    </a>
                </div>
                
                <div class="flex justify-between items-center mt-4">
                    <button 
                        @click="store.fetchResume(resume.id)"
                        class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold"
                    >
                        Editar
                    </button>


                    <button 
                        type="button"  @click="store.deleteResume(resume.id)"
                        class="text-red-400 hover:text-red-600 text-sm"
                    >
                        Excluir
                    </button>
                </div>
            </div>
          </div>

        </div>
      </div>

    </main>
  </div>
</template>