<script setup lang="ts">
// Importamos o componente que acabamos de criar
import ResumeEditor from './components/ResumeEditor.vue';
</script>

<template>
  <ResumeEditor />
</template>

<style>
/* Removemos as margens padrão do navegador para o editor ocupar 100% da tela */
body {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
</style>