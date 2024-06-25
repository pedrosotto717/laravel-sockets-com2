<template>
    <div class="chat-container">
      <ChatSidebar :chats="chats" @chat-selected="selectChat" />
      <MessageArea v-if="activeChat" :chat="activeChat" />
    </div>
</template>

<script>
import ChatSidebar from './chat/ChatSidebar.vue';
import MessageArea from './chat/MessageArea.vue';

export default {
    components: {
        ChatSidebar,
        MessageArea
    },
    data() {
        return {
        chats: [
            { id: 1, name: 'Chat 1', avatar: 'path/to/avatar1.png', lastMessage: 'Hola', messages: [] },
        ],
            activeChat: {}
        };
    },
    methods: {
        selectChat(chat) {
            this.activeChat = chat;
        }
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
    }
</style>
