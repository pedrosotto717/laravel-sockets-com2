<template>
    <section class="chat-area">
        <header class="chat-header">
            <!-- <h3>{{ chat.name }}</h3> -->
            <div class="profile-img">
                <img src="https://www.santandersmusic.com/media/magazine/shakira-disco-51-xl.webp" alt="" srcset="">
            </div>

            <h3 class="chat-name"><span class="name">Jose</span></h3>
            <menu-component :items="menuItems" />
        </header>

        <div class="messages-container">
            <div class="messages">
                <div v-for="message in messages" :key="message.id" class="message"
                    :class="{ 'mine': message.user_id === user_id, 'recived': message.user_id !== user_id }">
                    <span class="content">{{ message.text }} <span class="time">{{ message.updated_at }}</span></span>
                </div>
            </div>

            <div class="message-input-container">
                <input type="text" v-model="newMessage" @keyup.enter="sendMessage" placeholder="Escribe un mensaje..."
                    class="message-input">
                <button @click="sendMessage" class="send-button">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <block-user-dialog :value="isBlockUserDialogOpen" @update:value="isBlockUserDialogOpen = $event" />
    </section>
</template>

<script>
import MenuComponent from '../general/MenuComponent.vue';
import BlockUserDialog from '../dialogs/BlockUserDialog.vue';

export default {
    components: {
        'menu-component': MenuComponent,
        'block-user-dialog': BlockUserDialog,
    },
    props: {
        chat: Object
    },
    data() {
        return {
            isBlockUserDialogOpen: false,
            user_id: 1,
            newMessage: '',
            menuItems: [
                { title: 'Bloquear', action: this.openBlockUserDialog },
            ],
            messages: [
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Hola', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'A okey', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Voy a terminar la mayoria hoy', user_id: 2, updated_at: '24 jun - 17:45' },
                { text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dictum erat dui, et semper lectus scelerisque ut. Fusce pellentesque a ipsum ut aliquet. Nam euismod tellus quis elementum mollis. Fusce eget fermentum ligula. Donec consequat lorem velit, et condimentum nunc volutpat ac. Aenean elementum erat eget iaculis auctor. Praesent eget nunc at tellus ornare sagittis eget molestie dolor. Maecenas nulla elit, semper at tempus at, maximus vel est. In eu magna nec mi iaculis cursus sit amet ac enim. Nunc in tellus lobortis, interdum dolor vitae, congue odio. Nunc id diam ut felis interdum hendrerit non ut tortor. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. ', user_id: 1, updated_at: '24 jun - 17:45' },
                { text: 'Tampoco he adelantado tanto', user_id: 1, updated_at: '24 jun - 17:45' },
            ]
        };
    },
    methods: {
        sendMessage() {
            if (this.newMessage.trim() !== '') {
                this.chat.messages.push({
                    id: Date.now(),
                    text: this.newMessage,
                    isMine: true
                });
                this.newMessage = '';
            }
        },
        openBlockUserDialog() {
            this.isBlockUserDialogOpen = true;
        }
    }
};
</script>

<style scoped lang="scss">
@import "../../../css/styles/main";

.chat-area {
    width: 75%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

.chat-header {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 0 16px;
    background: $app-bg-light;
    border-bottom: $border;
    height: 57px;
    width: 100%;
}

.chat-name {
    flex-grow: 1;

    .name {
        cursor: pointer;
    }
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
}

.messages-container {
    position: relative;
    overflow-y: scroll;

    .messages {
        padding: 4px 16px;
        padding-bottom: 32px;
        height: calc(100% - 68px);
        overflow-y: scroll;
    }

    .message {
        display: flex;
    }

    .message.mine {
        justify-content: flex-end;
    }

    .message span.content {
        padding: 8px 16px 4px 16px;
        background-color: $input-bg;
        margin-bottom: 4px;
        border-radius: 4px;
        max-width: 600px;
        text-align: left;
        display: flex;
        flex-wrap: wrap;
    }

    .message.mine span.content {
        background-color: $main-color;
    }

    .message span.time {
        margin-left: 8px;
        font-size: 8px;
        display: inline-block;
        bottom: -4px;
        align-self: flex-end;
        flex-grow: 1;
        text-align: right;
    }


    .message-input-container {
        position: absolute;
        width: 100%;
        bottom: 0;
        background: $app-bg-light;
        padding: 4px 16px;
        display: flex;
        flex-direction: row;
        gap: 8px;
    }

    .message-input {
        flex-grow: 1;
    }

    .send-button {
        width: 46px;

        &:hover {
            background: black !important;
        }
    }

}
</style>