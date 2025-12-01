import { defineStore } from 'pinia';
import { ref } from 'vue';

const useResumeStore = defineStore('resume', () => {
    const resume = ref<any>(null);
    const loading = ref(false);
    const saving = ref(false); // Novo estado para mostrar "Salvando..."

    // Ação de Buscar (Já existia)
    const fetchResume = async (id: number) => {
        loading.value = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/resumes/${id}`);
            const data = await response.json();
            resume.value = data;
        } catch (error) {
            console.error('Erro ao buscar currículo:', error);
        } finally {
            loading.value = false;
        }
    };

    // Ação de Salvar (NOVA)
    const saveResume = async () => {
        if (!resume.value) return;

        saving.value = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/resumes/${resume.value.id}`, {
                method: 'PUT', // Método de atualização
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(resume.value) // Manda o objeto inteiro
            });

            if (response.ok) {
                alert('Salvo com sucesso!'); // Feedback simples por enquanto
            } else {
                alert('Erro ao salvar.');
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            alert('Erro de conexão.');
        } finally {
            saving.value = false;
        }
    };

    return { resume, loading, saving, fetchResume, saveResume };
});

export default useResumeStore;