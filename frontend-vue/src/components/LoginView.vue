<script setup lang="ts">
import { ref } from 'vue';
import useResumeStore from '../stores/resume';

const store = useResumeStore();

const email = ref('teste@exemplo.com'); 
const password = ref('password');       
const isRegistering = ref(false);       
const name = ref('');

const handleLogin = async () => {
    await store.login(email.value, password.value);
};

const handleRegister = async () => {
    await store.register(name.value, email.value, password.value);
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 font-sans">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      
      <h2 class="text-3xl font-bold text-center text-indigo-600 mb-2">
        {{ isRegistering ? 'Criar Conta' : 'Acesse sua Conta' }}
      </h2>
      <p class="text-center text-gray-500 mb-8">
        {{ isRegistering ? 'Comece a criar currículos incríveis.' : 'Bem-vindo de volta ao editor.' }}
      </p>

      <form @submit.prevent="isRegistering ? handleRegister() : handleLogin()" class="space-y-4">
        
        <div v-if="isRegistering">
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input v-model="name" type="text" required class="w-full border rounded p-2 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">E-mail</label>
            <input v-model="email" type="email" required class="w-full border rounded p-2 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Senha</label>
            <input v-model="password" type="password" required class="w-full border rounded p-2 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <button 
            type="submit" 
            :disabled="store.loading"
            class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition disabled:opacity-50"
        >
            {{ store.loading ? 'Carregando...' : (isRegistering ? 'Cadastrar' : 'Entrar') }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm">
        <button @click="isRegistering = !isRegistering" class="text-indigo-600 hover:underline">
            {{ isRegistering ? 'Já tem conta? Faça login.' : 'Não tem conta? Cadastre-se.' }}
        </button>
      </div>

    </div>
  </div>
</template>