import axios from '../axios';

export const fetchUserData = async () => {
    try {
        const response = await axios.get('/user');
        return response.data;
    } catch (error) {
        throw new Error(error.response.data.message || 'Failed to fetch user data');
    }
};