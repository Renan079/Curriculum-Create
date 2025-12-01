import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'

// 1. Cria o App
const app = createApp(App)

// 2. Cria o Pinia
const pinia = createPinia()

// 3. USA o Pinia (Importante: ANTES do mount)
app.use(pinia)

// 4. Monta na tela
app.mount('#app')