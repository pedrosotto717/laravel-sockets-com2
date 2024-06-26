import axios from '../axios';

export const fetchUserData = async () => {
    try {
        const response = await axios.get('/user');
        return response.data;
    } catch (error) {
        throw new Error(error.response.data.message || 'Failed to fetch user data');
    }
};

export const updateUserProfile = async (formData) => {
    try {
        const response = await axios.post('/user', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data;  // Esperamos recibir un mensaje de éxito de la API.
    } catch (error) {
        console.error('Failed to update profile:', error);
        throw new Error(error.response.data.message || 'Failed to update profile');
    }
};