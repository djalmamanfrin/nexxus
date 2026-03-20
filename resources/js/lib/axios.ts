import axios from 'axios';

// Pega o token CSRF do meta tag do Laravel
const token = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

// Cria instância do Axios
const api = axios.create({
    baseURL: import.meta.env.APP_URL || '/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token ?? '',
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
    withCredentials: true,
});
api.interceptors.request.use(
    (config) => {
        // Aqui você poderia adicionar token de auth Bearer se tiver API token
        return config;
    },
    (error) => Promise.reject(error),
);
api.interceptors.response.use(
    (response) => response,
    (error) => {
        const status = error.response?.status;

        switch (status) {
            case 401:
                console.error('Não autorizado!');
                // opcional: redirecionar para login
                break;
            case 422:
                console.warn('Validação falhou:', error.response.data.errors);
                break;
            case 419:
                console.warn('CSRF token expirou ou sessão inválida');
                break;
            case 500:
                console.error('Erro interno do servidor');
                break;
            default:
                console.error('Erro desconhecido', error.response);
        }
        return Promise.reject(error);
    },
);

export default api;
