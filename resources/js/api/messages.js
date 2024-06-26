import axios from '../axios';

export const fetchMessages = async (chatGroupId) => {
    try {
        const response = await axios.get(`messages/${chatGroupId}`);
        return response.data; // Asume que la respuesta viene en un formato adecuado para ser directamente utilizada.
    } catch (error) {
        console.error('Failed to fetch messages:', error);
        throw new Error(error.response.data.message || 'Failed to fetch messages');
    }
};

export const sendMessage = async (message, groupId) => {
    try {
        const response = await axios.post('messages/send', {
            message: message,
            group_id: groupId
        });
        return response.data;  // Supongamos que la API devuelve algún dato útil aquí.
    } catch (error) {
        console.error('Failed to send message:', error);
        throw new Error(error.response.data.message || 'Failed to send message');
    }
};

export const sendFileMessage = async (formData) => {
    try {
        const response = await axios.post('/api/messages/send-file', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data;  // Asume que la API devuelve algún dato útil aquí.
    } catch (error) {
        console.error('Failed to send file:', error);
        throw new Error(error.response.data.message || 'Failed to send file');
    }
};