<template>
    <div class="chat-container" v-if="loaded">
        <ChatSidebar :contacts="contacts" :user-data="userData" :chats="chats" @refresh-contacts="refreshContacts"
            @chat-selected="handleChatSelected" />
        <MessageArea v-if="chatHistory" :chatHistory="chatHistory" :activeGroup="activeGroup" :user-data="userData"
            :loading="loadingChat" />
    </div>
    <v-progress-circular v-else indeterminate color="primary"></v-progress-circular>
</template>

<script>
import { fetchUserData, fetchContacts, fetchGroups, fetchMessages } from '../api';
import ChatSidebar from './chat/ChatSidebar.vue';
import MessageArea from './chat/MessageArea.vue';

export default {
    components: {
        ChatSidebar,
        MessageArea
    },
    data() {
        return {
            loaded: false,
            userData: {},
            contacts: [],
            chats: [],
            chatHistory: {},
            activeGroup: {},
            loadingChat: false,
        };
    },
    async mounted() {
        this.loadInitialData();
    },
    methods: {
        async loadInitialData() {
            try {
                this.userData = await fetchUserData();
                this.contacts = await fetchContacts();
                this.chats = await fetchGroups();
                this.loaded = true;
            } catch (error) {
                console.error(error);
                alert(error);
                this.loaded = true;
            }
        },
        async refreshContacts() {
            await this.loadInitialData();
        },
        sortByUpdatedAt(items) {
            return items.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
        },
        async handleChatSelected(chat) {
            let chatGroupId = chat.pivot.chat_group_id
            try {
                this.loadingChat = true;
                const messages = await fetchMessages(chatGroupId);
                this.chatHistory = { ...this.chatHistory, messages, id: chatGroupId };
                this.activeGroup = chat;
                this.loadingChat = false;
            } catch (error) {
                console.error("Error fetching chat details:", error);
            }
        },
    }
};
</script>

<style scoped lang="scss">
@import "../../css/styles/main";

.chat-container {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    background-color: $app-bg;
    height: 80vh;
    overflow: hidden;
}
</style>
