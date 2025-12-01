<script setup lang="ts">
import { onMounted } from 'vue';
import useResumeStore  from '../stores/resume';

// 1. Conectamos com a nossa "memória" (Pinia Store)
const store = useResumeStore();

// 2. Assim que a tela abrir, pedimos para buscar o currículo ID 1
onMounted(() => {
    store.fetchResume(1);
});
</script>

<template>
  <div v-if="store.loading" class="flex items-center justify-center h-screen bg-gray-100">
    <div class="text-xl text-indigo-600 font-semibold animate-pulse">
      Carregando seu editor...
    </div>
  </div>

  <div v-else-if="store.resume" class="flex h-screen overflow-hidden font-sans">
    
    <div class="w-1/2 bg-gray-50 border-r border-gray-300 p-8 overflow-y-auto">
      
      <header class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Currículo</h2>
            <p class="text-gray-500 text-sm">Edite os campos abaixo.</p>
        </div>
        
        <button 
            @click="store.saveResume()"
            :disabled="store.saving"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow flex items-center gap-2 transition disabled:opacity-50"
        >
            <span v-if="store.saving">💾 Salvando...</span>
            <span v-else>💾 Salvar</span>
        </button>
      </header>

      <div v-for="section in store.resume.sections" :key="section.id" class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        
        <h3 class="font-bold text-lg text-indigo-600 mb-4 border-b pb-2 flex items-center gap-2">
            <span class="text-xl">📝</span>
            {{ section.title }}
        </h3>

        <div v-if="section.type === 'personal'" class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Nome Completo</label>
                <input v-model="section.content.full_name" type="text" class="input-padrao">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Título Profissional</label>
                <input v-model="section.content.headline" type="text" class="input-padrao" placeholder="Ex: Desenvolvedor Full Stack">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">E-mail</label>
                    <input v-model="section.content.email" type="text" class="input-padrao">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Telefone</label>
                    <input v-model="section.content.phone" type="text" class="input-padrao">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Cidade/Estado</label>
                <input v-model="section.content.city" type="text" class="input-padrao">
            </div>
        </div>

        <div v-if="section.type === 'experience'" class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Empresa</label>
                <input v-model="section.content.company" type="text" class="input-padrao">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Cargo</label>
                <input v-model="section.content.role" type="text" class="input-padrao">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Início</label>
                    <input v-model="section.content.date_start" type="text" class="input-padrao">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Fim</label>
                    <input v-model="section.content.date_end" type="text" class="input-padrao">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">Descrição</label>
                <textarea v-model="section.content.description" rows="4" class="input-padrao"></textarea>
            </div>
        </div>

      </div>
    </div>

    <div class="w-1/2 bg-gray-700 p-8 overflow-y-auto flex justify-center items-start">
        
        <div 
            class="bg-white shadow-2xl p-10 min-h-[29.7cm] w-[21cm] transform scale-90 origin-top" 
            :style="{ fontFamily: store.resume.font_family }"
        >
            <div v-for="section in store.resume.sections" :key="section.id" class="mb-6">
                
                <div v-if="section.type === 'personal'" class="text-center border-b-2 border-gray-200 pb-6 mb-6">
                    <h1 class="text-4xl font-bold uppercase tracking-tight" :style="{ color: store.resume.primary_color }">
                        {{ section.content.full_name }}
                    </h1>
                    <p class="text-xl text-gray-600 mt-2 font-light">{{ section.content.headline }}</p>
                    
                    <div class="mt-4 text-sm text-gray-500 flex justify-center gap-3 flex-wrap">
                        <span class="flex items-center gap-1">📧 {{ section.content.email }}</span>
                        <span class="text-gray-300">|</span> 
                        <span class="flex items-center gap-1">📱 {{ section.content.phone }}</span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1">📍 {{ section.content.city }}</span>
                    </div>
                </div>

                <div v-if="section.type === 'experience'">
                    <h2 class="text-lg font-bold uppercase tracking-wider mb-4 border-b-2 pb-1" 
                        :style="{ color: store.resume.primary_color, borderColor: store.resume.primary_color }">
                        {{ section.title }}
                    </h2>
                    
                    <div class="mb-4">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-800 text-lg">{{ section.content.role }}</h3>
                            <span class="text-sm font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ section.content.date_start }} - {{ section.content.date_end }}
                            </span>
                        </div>
                        <div class="text-indigo-600 font-medium mb-2 italic">{{ section.content.company }}</div>
                        <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line text-justify">
                            {{ section.content.description }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

  </div>
</template>

<style scoped>
/* Classe utilitária simples para os inputs não ficarem repetindo código Tailwind */
.input-padrao {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out;
}
</style>