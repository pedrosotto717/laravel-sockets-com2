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
import { connectToChannel, disconnectFromChannel } from '@/plugins/pusher';
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
            activeGroupId: null,
        };
    },
    async mounted() {
        this.loadInitialData();
    },
    watch: {
        activeGroupId(value) {
            if(value) {
                this.handlerConnectToChannel(value);
            }
        }
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

        async handleChatSelected(chat, newLoad=false) {
            let chatGroupId = chat.id;
            this.activeGroupId = chatGroupId;

            if(newLoad) {
                this.loadingChat = true;
                this.activeGroup = chat;
            }

            try {
                const messages = await fetchMessages(chatGroupId);
                this.chatHistory = { ...this.chatHistory, messages, id: chatGroupId };
                this.loadingChat = false;
            } catch (error) {
                console.error("Error fetching chat details:", error);
            }
        },

        handlerConnectToChannel(activeGroupId) {
            if (activeGroupId) {
                const channelAlias = `new-message.${activeGroupId}`,
                self = this;

                this.channelName = `chat-group.${activeGroupId}`;
                this.channel = connectToChannel(this.channelName);
                this.channel.bind(channelAlias, function (val) {
                    self.handleChatSelected({id: activeGroupId})
                });
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
