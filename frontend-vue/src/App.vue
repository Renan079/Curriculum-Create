<script setup lang="ts">
import { ref, onMounted } from 'vue';

// 1. Definimos um estado reativo para guardar nossos itens
// A interface define o formato dos dados para o TypeScript não reclamar
interface Item {
  id: number;
  nome: string;
  descricao: string;
}

const items = ref<Item[]>([]);
const loading = ref(true);
const error = ref('');

// 2. Função para buscar os dados na API do Laravel
const fetchItems = async () => {
  try {
    // Atenção: O endereço deve ser o mesmo onde seu Laravel está rodando
    const response = await fetch('http://127.0.0.1:8000/api/items');
    
    if (!response.ok) {
      throw new Error('Erro na resposta da rede');
    }
    
    // Converte a resposta para JSON e salva na variável 'items'
    items.value = await response.json();
  } catch (err) {
    console.error("Erro ao buscar itens:", err);
    error.value = 'Não foi possível carregar os itens. Verifique se o Backend está rodando.';
  } finally {
    loading.value = false;
  }
};

// 3. O onMounted executa assim que a tela carrega
onMounted(() => {
  fetchItems();
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
      
      <header class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-indigo-600 mb-2">Meu Novo Sistema</h1>
        <p class="text-gray-600">Lista de itens vindos diretamente do Laravel</p>
      </header>

      <div v-if="loading" class="text-center text-gray-500 text-xl">
        Carregando dados...
      </div>

      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center">
        {{ error }}
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          v-for="item in items" 
          :key="item.id" 
          class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-indigo-500"
        >
          <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ item.nome }}</h2>
          <p class="text-gray-600">{{ item.descricao }}</p>
          <div class="mt-4 text-xs text-gray-400">ID: {{ item.id }}</div>
        </div>
      </div>

    </div>
  </div>
</template>

<style scoped>
/* Não precisamos de CSS extra aqui, o Tailwind resolve tudo! */
</style>