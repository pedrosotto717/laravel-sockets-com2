<template>
    <section class="chat-area" :class="{ 'loading': loading || !chatHistory || !activeGroup }">
        <!-- Encabezado solo se muestra si hay un chat activo y no está cargando -->
        <header class="chat-header" v-if="chatHistory && !loading">
            <div class="profile-img">
                <img :src="userData.profile_photo_url || defaultProfile" alt="">
            </div>
            <h3 class="chat-name"><span class="name">{{ chatName }}</span></h3>
            <menu-component :items="menuItems" />
        </header>

        <!-- Área de mensajes solo se muestra si hay mensajes y no está cargando -->
        <div class="messages-container" v-if="chatHistory && chatHistory.messages && !loading">
            <div class="messages">
                <div v-for="message in chatHistory.messages.messages" :key="message.id" class="message"
                    :class="{ 'mine': message.user_id === userData.id, 'recived': message.user_id !== userData.id }">
                    <span class="content">
                        <template v-if="message.message">
                            {{ message.message }}
                        </template>
                        <template v-else-if="message.file_url">
                            <a :href="message.file_url" target="_blank">
                                <i class="fas fa-paperclip icon"></i>
                                Archivo{{ getFileExtension(message.file_url) }}
                            </a>
                        </template>
                        <span class="time">{{ formatDate(message.updated_at) }}</span>
                    </span>
                </div>
            </div>

            <div class="message-input-container">
                <input type="text" v-model="newMessage" @keyup.enter="sendMessage" placeholder="Escribe un mensaje..."
                    class="message-input">
                <input type="file" @change="attachFile" hidden ref="fileInput" />
                
                <button @click="triggerFileInput" class="attach-button">
                    <i class="fas fa-paperclip"></i>
                </button>
                
                <button @click="sendMessage" class="send-button">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Muestra el loader si está cargando o si no hay chat activo -->
        <v-progress-circular v-if="loading || !chatHistory  || !activeGroup" indeterminate color="primary"></v-progress-circular>

        <block-user-dialog :value="isBlockUserDialogOpen" @update:value="isBlockUserDialogOpen = $event" />
    </section>
</template>

<script>
import { sendMessage, sendFileMessage } from '@/api';  
import defaultProfile from '@/assets/images/profile-default.jpg';
import MenuComponent from '../general/MenuComponent.vue';
import BlockUserDialog from '../dialogs/BlockUserDialog.vue';

export default {
    components: {
        'menu-component': MenuComponent,
        'block-user-dialog': BlockUserDialog,
    },
    props: {
        chatHistory: Object,
        activeGroup: Object,
        userData: Object,
        loading: Boolean,
    },
    data() {
        return {
            loadingNoData: true,
            defaultProfile: defaultProfile,
            newMessage: '',
            menuItems: [
                { title: 'Bloquear', action: this.openBlockUserDialog },
                { title: 'Cambiar Nombre', action: this.openBlockUserDialog },
                { title: 'Salir del Grupo', action: this.openBlockUserDialog },
            ],
            isBlockUserDialogOpen: false,
        };
    },
    computed: {
        chatName() {
            if (this.activeGroup.name) {
                return this.activeGroup.name;
            } else if(this.activeGroup.users) {
                // Filtrar para no mostrar el nombre del usuario actual
                const otherUser = this.activeGroup.users.find(user => user.id !== this.userData.id);
                return otherUser ? otherUser.name : 'Unknown';
            }
        },
    },
    methods: {
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        async sendMessage() {
            if (this.newMessage.trim()) {
                await sendMessage(this.newMessage, this.activeGroup.id);
                this.newMessage = '';
                // new Audio(sendSound).play();  // Reproduce el sonido
            }
        },
        async attachFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            try {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('group_id', this.activeGroup.id);
                const result = await sendFileMessage(formData);
                alert('Archivo cargado correctamente');
                // Aquí puedes manejar la actualización del chat con el nuevo mensaje/archivo enviado
            } catch (error) {
                alert('Error al cargar el archivo: ' + error.message);
            }
        },
        formatDate(date) {
            const d = new Date(date);
            return `${d.getDate()} ${d.toLocaleString('default', { month: 'short' })} - ${d.getHours()}:${d.getMinutes()}`;
        },
        openBlockUserDialog() {
            this.isBlockUserDialogOpen = true;
        },
        getFileExtension(url) {
            const ext = url.split('.').pop();
            return ext ? `.${ext}` : '';
        },
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

    &.loading {
        justify-content: center;
        align-items: center;
    }
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
    height: 100%;

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

        .icon {
            display: inline-block;
            margin-right: 8px;
        }
        
        a {
            color: $text-color;
        }
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

    .send-button, .attach-button {
        width: 46px;

        &:hover {
            background: black !important;
        }
    }

}
</style>