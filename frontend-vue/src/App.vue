<script setup lang="ts">
import ResumeEditor from './components/ResumeEditor.vue';
import LoginView from './components/LoginView.vue';
import useResumeStore from './stores/resume';

const store = useResumeStore();
</script>

<template>
  <ResumeEditor v-if="store.token" />
  <LoginView v-else />
</template>

<style>
body { margin: 0; padding: 0; box-sizing: border-box; }
</style>