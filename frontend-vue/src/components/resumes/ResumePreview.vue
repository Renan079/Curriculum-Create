<script setup>
import { ref, computed } from 'vue';
import Moderno from './Templates/Moderno.vue';
import Classico from './Templates/Classico.vue';

const props = defineProps({
    resumeData: Object // Dados vindos do banco
});

// Lista de templates disponíveis
const templates = {
    moderno: Moderno,
    classico: Classico
};

const selectedTemplate = ref('moderno'); // Padrão
</script>

<template>
    <div class="flex gap-4">
        <aside class="w-64 bg-gray-100 p-4 rounded">
            <h3 class="font-bold mb-4">Escolha o Modelo:</h3>
            
            <button 
                @click="selectedTemplate = 'moderno'"
                :class="{'bg-blue-500 text-white': selectedTemplate === 'moderno'}"
                class="w-full p-2 mb-2 rounded border"
            >
                Moderno
            </button>
            
            <button 
                @click="selectedTemplate = 'classico'"
                :class="{'bg-blue-500 text-white': selectedTemplate === 'classico'}"
                class="w-full p-2 mb-2 rounded border"
            >
                Clássico
            </button>
        </aside>

        <main class="flex-1 bg-gray-200 p-8 overflow-auto">
            <component 
                :is="templates[selectedTemplate]" 
                :resume="resumeData" 
            />
        </main>
    </div>
</template>