<template>
    <div class="chat-container" v-if="loaded">
        <ChatSidebar :contacts="contacts" :user-data="userData" :chats="chats"
            :contactsWithoutChat="contactsWithoutChat" @refresh-contacts="refreshContacts"
            @chat-selected="handleChatSelected" @start-conversation="handleStartConversation" />

        <MessageArea v-if="chatHistory" :chatHistory="chatHistory" :activeGroup="activeGroup" :user-data="userData"
            :loading="loadingChat" @chat-created="handleChatSelected" @refresh-contacts="refreshContactsChats" />
    </div>
    <v-progress-circular v-else indeterminate color="primary"></v-progress-circular>
</template>

<script>
import { connectToChannel, disconnectFromChannel } from '@/plugins/pusher';
import { fetchUserData, fetchContacts, fetchGroups, fetchMessages, fetchContactsWithoutChat } from '../api';
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
            contactsWithoutChat: {},
            loadingChat: false,
            activeGroupId: null,
            NotificationChatChannel: null,
            NotificationChatChannelName: 'new-message',
        };
    },
    async mounted() {
        this.loadInitialData();

        console.log('User Data mounted:', this.userData);
        if(this.userData?.id && this.chats.length > 0){
            this.handlerNotificationConnectToChannel(this.userData.id);
        }
    },
    watch: {
        activeGroupId(value) {
            if (value) {
                this.handlerConnectToChannel(value);
            }
        },
        userData(value) {
            console.log('Chats 1 ------> ', this.chats);
            if (value.id && this.chats.length > 0) {
                this.handlerNotificationConnectToChannel(value.id);
            }
        },
        chats(value) {
            console.log('Chats 2 ------> ', this.chats);
            if(this.userData?.id && this.chats.length > 0){
                this.handlerNotificationConnectToChannel(this.userData.id);
            }
        }
    },

    beforeDestroy() {
        this.NotificationChatChannel = disconnectFromChannel(this.NotificationChatChannel, this.NotificationChatChannelName);
    },

    methods: {
        async loadInitialData() {
            try {
                this.userData = await fetchUserData();
                this.contacts = await fetchContacts();
                this.contactsWithoutChat = await fetchContactsWithoutChat();
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
        async refreshContactsChats() {
            this.chatHistory = {};
            this.activeGroup = {};
            await this.loadInitialData();
        },
        sortByUpdatedAt(items) {
            return items.sort((a, b) => new Date(b.updated_at) - new Date(a.updated_at));
        },

        async handleChatSelected(chat, newLoad = false) {
            let chatGroupId = chat.id;
            this.activeGroupId = chatGroupId;

            if (newLoad) {
                this.loadingChat = true;
                this.activeGroup = chat;
                this.refreshContacts();
            }

            try {
                const messages = await fetchMessages(chatGroupId, this.userData.email);
                this.chatHistory = { ...this.chatHistory, messages, id: chatGroupId };
                this.loadingChat = false;
            } catch (error) {
                console.error("Error fetching chat details:", error);
            }

            this.chats = this.chats.map(c => {
                if (c.id === chatGroupId) {
                    c.newMessages = false;
                }

                return c;
            });
        },

        handleStartConversation(contactInfo) {
            console.log('Starting conversation with:', contactInfo);
            this.activeGroup = { name: "", users:[contactInfo], id:null}
            this.chatHistory = {}
        },

        handlerConnectToChannel(activeGroupId) {
            if (activeGroupId) {
                const channelAlias = `new-message.${activeGroupId}`,
                    self = this;

                this.channelName = `chat-group.${activeGroupId}`;
                this.channel = connectToChannel(this.channelName);
                this.channel.bind(channelAlias, function (val) {
                    self.handleChatSelected({ id: activeGroupId })
                });
            }
        },

        handlerNotificationConnectToChannel(userId) {
            if (userId) {
                const channelAlias = `new-notification`,
                    self = this;

                this.NotificationChatChannelName = `user-notifications.${userId}`;
                this.NotificationChatChannel = connectToChannel(this.NotificationChatChannelName);

                this.NotificationChatChannel.bind(channelAlias,  (val) => {
                    const groupId = val.chatGroupId;

                    if(groupId > 0) {
                       this.chats = this.chats?.map(chat => {
                            if(chat.id === groupId) {
                                chat.newMessages = true;
                            }
                            return chat;
                        });
                    }
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
