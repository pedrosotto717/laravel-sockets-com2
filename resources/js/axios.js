// resources/js/axios.js
import axios from 'axios';

const axiosInstance = axios.create({
    baseURL: '??', // Cambia esto por la URL de tu API
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    }
});

export default axiosInstance;
