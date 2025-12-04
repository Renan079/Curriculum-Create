import { defineStore } from 'pinia';
import { ref } from 'vue';

const useResumeStore = defineStore('resume', () => {
    // --- ESTADOS (State) ---
    const resume = ref<any>(null);      // O currículo atual sendo editado (Editor)
    const resumes = ref<any[]>([]);     // A lista de currículos (Dashboard)
    const loading = ref(false);
    const saving = ref(false);
    const token = ref(localStorage.getItem('auth_token') || '');

    // --- AÇÕES DE AUTENTICAÇÃO ---

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
                
                // Ao logar, limpamos o currículo atual para mostrar o Dashboard
                resume.value = null; 
                await fetchResumes(); 
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
                
                // Ao registrar, vamos para o Dashboard
                resume.value = null;
                await fetchResumes();
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
        resumes.value = [];
        localStorage.removeItem('auth_token');
        window.location.reload();
    };

    // --- AÇÕES DO DASHBOARD (MEUS CURRÍCULOS) ---

    const fetchResumes = async () => {
        if (!token.value) return;
        loading.value = true;
        try {
            const response = await fetch('http://127.0.0.1:8000/api/my-resumes', {
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}` 
                }
            });
            
            if (response.status === 401) { logout(); return; }
            
            resumes.value = await response.json();
        } catch (error) { console.error(error); } 
        finally { loading.value = false; }
    };

    const createResume = async (title: string = 'Novo Currículo') => {
        loading.value = true;
        try {
            const response = await fetch('http://127.0.0.1:8000/api/resumes', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}` 
                },
                body: JSON.stringify({ title })
            });
            const newResume = await response.json();
            // Após criar, abre o editor imediatamente com o novo currículo
            await fetchResume(newResume.id);
        } catch (e) { alert('Erro ao criar'); } 
        finally { loading.value = false; }
    };

    const deleteResume = async (id: number) => {
    if (!confirm('Tem certeza? Isso apagará o currículo para sempre.')) return;
    
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/resumes/${id}`, {
            method: 'DELETE',
            headers: { 
                'Authorization': `Bearer ${token.value}`,
                'Accept': 'application/json' // Importante para o Laravel retornar JSON se der erro
            }
        });

        // VERIFICAÇÃO CRÍTICA ADICIONADA
        if (!response.ok) {
            // Se o status for 4xx ou 5xx, forçamos o erro
            throw new Error('Falha na resposta do servidor');
        }

        // Só remove da tela se o servidor confirmou que apagou (ou deu 200/204)
        resumes.value = resumes.value.filter(r => r.id !== id);
        
    } catch (e) { 
        console.error(e); // Bom para você ver o erro real no console (F12)
        alert('Erro ao excluir. Verifique o console.'); 
    }
};

    // --- AÇÕES DO EDITOR (SINGLE RESUME) ---

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
                console.error("Sem permissão");
                return; 
            }

            const data = await response.json();
            resume.value = data;
        } catch (error) { console.error(error); } 
        finally { loading.value = false; }
    };

    // --- AQUI ESTA A FUNÇÃO QUE FALTAVA ---
    const fetchUserResume = async () => {
        if (!token.value) return;
        
        loading.value = true;
        try {
            // Busca a lista de currículos
            const response = await fetch('http://127.0.0.1:8000/api/my-resumes', {
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}` 
                }
            });
            
            if (response.status === 401) { logout(); return; }

            const data = await response.json();

            // Se tiver currículo, carrega o primeiro
            if (data.length > 0) {
                await fetchResume(data[0].id);
            } else {
                resume.value = null; // Nenhum currículo encontrado
            }
        } catch (error) {
            console.error("Erro ao buscar lista:", error);
        } finally {
            loading.value = false;
        }
    };

    const saveResume = async () => {
        if (!resume.value) return;
        saving.value = true;
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/resumes/${resume.value.id}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token.value}`
                },
                body: JSON.stringify(resume.value)
            });
            
            if (response.ok) {
                alert('Salvo com sucesso!');
            }
        } catch (error) { alert('Erro ao salvar'); } 
        finally { saving.value = false; }
    };

    const closeEditor = () => {
        resume.value = null; // Fecha o editor
        fetchResumes(); // Atualiza o dashboard com dados novos se houver
    };

    // --- MANIPULAÇÃO DE SEÇÕES ---

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
        if (resume.value) {
            resume.value.sections = resume.value.sections.filter((s: any) => s !== section);
        }
    };

    // --- RETORNO (EXPORTAÇÃO) ---
    return { 
        // Estados
        resume, resumes, loading, saving, token, 
        // Auth
        login, register, logout, 
        // Dashboard
        fetchResumes, createResume, deleteResume,
        // Editor
        fetchResume, saveResume, closeEditor, 
        fetchUserResume, // <--- AGORA VAI FUNCIONAR POIS A FUNÇÃO EXISTE ACIMA
        // Seções
        addSection, removeSection 
    };
});

export default useResumeStore;