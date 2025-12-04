<script setup lang="ts">
import ResumeEditor from './components/ResumeEditor.vue';
import LoginView from './components/LoginView.vue';
import DashboardView from './components/DashboardView.vue'; // <--- Importamos o novo componente
import useResumeStore from './stores/resume';

const store = useResumeStore();
</script>

<template>
  <div v-if="store.token">
    <ResumeEditor v-if="store.resume" /> <DashboardView v-else />             
  </div>

  <div v-else>
    <LoginView />
  </div>
</template>

<style>
body { margin: 0; padding: 0; box-sizing: border-box; }
</style>