import axios from '../axios';

export const fetchContacts = async () => {
    try {
        const response = await axios.get('/contacts');
        return response.data.contacts;
    } catch (error) {
        throw new Error(error.response.data.message || 'Failed to fetch contacts');
    }
};

export const addContact = async (email, name) => {
    try {
        const response = await axios.post('/contacts', { email, name });
        return { success: true, data: response.data };
    } catch (error) {
        if (error.response && error.response.data) {
            return { success: false, errors: error.response.data };
        } else {
            return { success: false, errors: { general: ['An unexpected error occurred.'] } };
        }
    }
};

export const fetchContactsWithoutChat = async () => {
    try {
        const response = await axios.get('/contacts-without-chat');
        return response.data.contacts;
    } catch (error) {
        throw new Error(error.response.data.message || 'Failed to fetch contacts without chat');
    }
};

export const deleteContact = async (email) => {
    try {
        const response = await axios.delete(`/delete-contact`, { data: { email: email } });
        return response.data;
    } catch (error) {
        console.error('Failed to delete contact:', error);
        throw new Error(error.response.data.message || 'Failed to delete contact');
    }
};
