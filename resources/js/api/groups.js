import axios from '../axios';

export const createGroup = async (name, description, emails) => {
    try {
        const response = await axios.post('/groups', { name, description, emails });
        return { success: true, data: response.data };
    } catch (error) {
        if (error.response && error.response.data) {
            // Extracción y retorno de mensajes de error estructurados para cada campo
            return { success: false, errors: error.response.data };
        } else {
            return { success: false, errors: { general: ['An unexpected error occurred.'] } };
        }
    }
};

export const fetchGroups = async () => {
    try {
        const response = await axios.get('/groups');
        return response.data.groups;
    } catch (error) {
        console.error('Failed to fetch groups:', error);
        throw new Error(error.response.data.message || 'Failed to fetch groups');
    }
};

export const leaveGroup = async (groupId) => {
    try {
        const response = await axios.post(`/leave-group`, { group_id: groupId });
        return response.data;
    } catch (error) {
        console.error('Failed to leave group:', error);
        throw new Error(error.response.data.message || 'Failed to leave group');
    }
};