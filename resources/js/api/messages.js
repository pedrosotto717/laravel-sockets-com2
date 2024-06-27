import axios from '../axios';
import { decryptText, encryptText } from '@/plugins/cryptoAES';

export const fetchMessages = async (chatGroupId, userEmail) => {
    try {
        const response = await axios.get(`messages/${chatGroupId}`);

        //here is necessary decrypt the messages before return it
        const messages = response.data.messages.map(msg => {
            let decryptedMessage = decryptText(msg.message, userEmail);
            //I need to delete the first and last character of the decrypted message because it has a double quote
            if(decryptedMessage) {
                decryptedMessage = decryptedMessage.substring(1, decryptedMessage.length - 1);
            }

            return { ...msg, message: decryptedMessage};
        });
        
        return {...response.data, messages}; // Asume que la respuesta viene en un formato adecuado para ser directamente utilizada.
    } catch (error) {
        console.error('Failed to fetch messages:', error);
        throw new Error(error.response.data.message || 'Failed to fetch messages');
    }
};

export const sendMessage = async (message, groupId, userEmail, recipientEmail = "") => {
    try {
        //here is necessary encrypt the message before send it
        const encryptedMessage = encryptText(message, userEmail);
        
        let body = {message: encryptedMessage}

        if(!groupId) {
            body.recipient_email = recipientEmail;
        } else {
            body.group_id = groupId;
        }

        const response = await axios.post('messages/send', body);

        return response.data;
    } catch (error) {
        console.error('Failed to send message:', error);
        throw new Error(error.response.data.message || 'Failed to send message');
    }
};

export const sendFileMessage = async (formData) => {
    try {
        const response = await axios.post('messages/attachments', formData, {
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