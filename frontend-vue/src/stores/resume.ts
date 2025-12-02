import { defineStore } from 'pinia';
import { ref } from 'vue';

const useResumeStore = defineStore('resume', () => {
    const resume = ref<any>(null);
    const loading = ref(false);
    const saving = ref(false);
    
    const token = ref(localStorage.getItem('auth_token') || '');

    // --- FUNÇÃO INTELIGENTE: Descobre qual currículo carregar ---
    const fetchUserResume = async () => {
        if (!token.value) return;
        
        loading.value = true;
        try {
            // 1. Pergunta: "Quais são meus currículos?"
            const response = await fetch('http://127.0.0.1:8000/api/my-resumes', {
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}` 
                }
            });
            
            if (response.status === 401) { logout(); return; }

            const resumes = await response.json();

            // 2. Se tiver currículo, carrega o primeiro
            if (resumes.length > 0) {
                await fetchResume(resumes[0].id);
            } else {
                resume.value = null; // Nenhum currículo encontrado
            }
        } catch (error) {
            console.error("Erro ao buscar lista:", error);
        } finally {
            loading.value = false;
        }
    };

    // --- AUTENTICAÇÃO ---
    const login = async (email: string, password: string) => {
        loading.value = true;
        try {
            const response = await fetch('http://127.0.0.1:8000/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            const data = await response.json();
            
            if (response.ok) {
                token.value = data.access_token;
                localStorage.setItem('auth_token', data.access_token);
                
                // MUDANÇA CRUCIAL: Busca o currículo DO USUÁRIO (e não o ID 1)
                await fetchUserResume();
            } else {
                alert(data.message || 'Erro no login');
            }
        } catch (e) { alert('Erro de conexão'); } 
        finally { loading.value = false; }
    };

    const register = async (name: string, email: string, password: string) => {
        loading.value = true;
        try {
            const response = await fetch('http://127.0.0.1:8000/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name, email, password })
            });
            const data = await response.json();
            
            if (response.ok) {
                token.value = data.access_token;
                localStorage.setItem('auth_token', data.access_token);
                
                // Busca o currículo novo que acabou de ser criado
                await fetchUserResume();
                
                alert('Conta criada com sucesso!');
            } else {
                alert('Erro ao cadastrar: ' + (data.message || 'Verifique os dados'));
            }
        } catch (e) { alert('Erro de conexão'); } 
        finally { loading.value = false; }
    };

    const logout = () => {
        token.value = '';
        resume.value = null;
        localStorage.removeItem('auth_token');
        window.location.reload(); // Recarrega para voltar ao Login
    };

    // --- CURRÍCULO (CRUD) ---
    const fetchResume = async (id: number) => {
        loading.value = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/resumes/${id}`, {
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}` 
                }
            });
            
            if (response.status === 401 || response.status === 403) { 
                console.error("Sem permissão para este currículo");
                return; 
            }

            const data = await response.json();
            resume.value = data;
        } catch (error) { console.error(error); } 
        finally { loading.value = false; }
    };

    const saveResume = async () => {
        if (!resume.value) return;
        saving.value = true;
        try {
            await fetch(`http://127.0.0.1:8000/api/resumes/${resume.value.id}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}`
                },
                body: JSON.stringify(resume.value)
            });
            alert('Salvo com sucesso!');
        } catch (error) { alert('Erro ao salvar'); } 
        finally { saving.value = false; }
    };

    const addSection = (type: string) => {
        let newContent = {};
        let title = '';
        if (type === 'experience') {
            title = 'Nova Experiência';
            newContent = { role: '', company: '', date_start: '', date_end: '', description: '' };
        }
        if (resume.value) {
            resume.value.sections.push({
                id: null, resume_id: resume.value.id, type: type, title: title,
                order_index: resume.value.sections.length + 1, content: newContent
            });
        }
    };

    const removeSection = async (section: any) => {
        if (!confirm('Remover item?')) return;
        if (section.id) {
            await fetch(`http://127.0.0.1:8000/api/sections/${section.id}`, { 
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token.value}` }
            });
        }
        resume.value.sections = resume.value.sections.filter((s: any) => s !== section);
    };

    return { 
        resume, loading, saving, token, 
        login, register, logout, 
        fetchResume, saveResume, addSection, removeSection, 
        fetchUserResume // IMPORTANTE: Exportando para usar no Editor
    };
});

export default useResumeStore;