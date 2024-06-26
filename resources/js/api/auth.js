import axios from '../axios';

export const login = async (userData) => {
    try {
        const response = await axios.post('/login', userData);
        localStorage.setItem('token', response.data.token); // Guardamos el token en el almacenamiento local
        return response.data;
    } catch (error) {
        throw error.response.data.message; // Lanzamos el error para ser manejado en el componente
    }
};

export const register = async (userData) => {
    try {
        const response = await axios.post('/contacts', userData);
        localStorage.setItem('token', response.data.token); // Guardamos el token en el almacenamiento local
        return response.data;
    } catch (error) {
        throw error.response.data.message; // Lanzamos el error para ser manejado en el componente
    }
};
