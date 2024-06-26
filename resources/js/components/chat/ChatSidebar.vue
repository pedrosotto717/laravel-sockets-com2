<template>
    <aside class="chat-sidebar">
        <div class="profile-bar">
            <div class="profile-img" @click="openEditProfileDialog">
                <img :src="userData.profile_photo_url || defaultProfile" alt="" srcset="">
            </div>
            <div class="more-options">
                <menu-component :items="menuItems" />
                <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
            </div>
        </div>

        <div class="chats-container">
            <div class="chat-container" v-for="chat in chats" :key="chat.id" @click="handleChatSelected(chat)">
                <div class="profile-img">
                    <img :src="chat.profile_photo_url || defaultProfile" alt="Profile Image">
                </div>
                <div class="chat-name">
                    {{ getChatName(chat) }}
                </div>
            </div>
        </div>

        <!-- Dialogs -->
        <add-user-dialog v-model="isAddUserDialogOpen" @contact-added="handleContactAdded" />
        <create-group-dialog v-model="isCreateGroupDialogOpen" :contacts="contacts" :user-data="userData"
            @group-created="handleGroupCreated" />
        <edit-profile-dialog v-model="isEditProfileDialogOpen" :contacts="contacts" :user-data="userData" />
        <start-conversation-dialog v-model="isStartConversationDialogOpen" :contacts="contacts" @contact-selected="handleContactSelected"/>
    </aside>
</template>

<script>
import defaultProfile from '@/assets/images/profile-default.jpg';
import MenuComponent from '../general/MenuComponent.vue';
import AddUserDialog from '../dialogs/AddUserDialog.vue';
import CreateGroupDialog from '../dialogs/CreateGroupDialog.vue';
import EditProfileDialog from '../dialogs/EditProfileDialog.vue';
import StartConversationDialog from '../dialogs/StartConversationDialog.vue';

export default {
    components: {
        MenuComponent,
        AddUserDialog,
        CreateGroupDialog,
        EditProfileDialog,
        StartConversationDialog,
    },
    props: {
        contacts: Array,
        chats: Array,
        userData: Object
    },
    data() {
        return {
            defaultProfile: defaultProfile,
            menuItems: [
                { title: 'Iniciar Conversación', action: this.openStartConversationDialog },
                { title: 'Agregar contacto', action: this.openAddUserDialog },
                { title: 'Crear grupo', action: this.createGroup },
                { title: 'Cambiar opciones de perfil', action: this.openEditProfileDialog },
                { title: 'Cerrar Sesión', action: this.openEditProfileDialog },
            ],
            isAddUserDialogOpen: false,
            isCreateGroupDialogOpen: false,
            isEditProfileDialogOpen: false,
            isStartConversationDialogOpen: false,
        };
    },
    methods: {
        openAddUserDialog() {
            this.isAddUserDialogOpen = true;
        },
        createGroup() {
            this.isCreateGroupDialogOpen = true;
        },
        openEditProfileDialog() {
            this.isEditProfileDialogOpen = true;
        },
        openStartConversationDialog() {
            this.isStartConversationDialogOpen = true;
        },

        getImageUrl(contact) {
            return contact.profile_photo_url ? contact.profile_photo_url : this.defaultProfile;
        },

        handleContactAdded() {
            this.isAddUserDialogOpen = false; // Cerrar el diálogo
            this.$emit('refresh-contacts');
        },
        handleGroupCreated() {
            this.isCreateGroupDialogOpen = false; // Cerrar el diálogo
            this.$emit('refresh-contacts');
        },
        handleChatSelected(chat) {
            this.$emit('chat-selected', chat, true);
        },
        handleContactSelected(contact) {
            console.log('Contact selected for conversation:', contact);
            // Logica
        },

        getChatName(chat) {
            if (chat.name) {
                return chat.name;
            } else {
                // Filtrar para no mostrar el nombre del usuario actual
                const otherUser = chat.users.find(user => user.id !== this.userData.id);
                return otherUser ? otherUser.name : 'Unknown';
            }
        },
    }
};
</script>

<style scoped lang="scss">
@import "../../../css/styles/main";

.chat-sidebar {
    width: 25%;
    color: white;
    overflow-y: auto;
    border-right: $border;
    position: relative;
    height: 100%;
}

.profile-bar {
    padding: 8px 16px;
    height: 57px;
    display: flex;
    display: flex;
    justify-content: space-between;
    background: $app-bg-light;
    border-bottom: $border;
    align-items: center;
    position: absolute;
    width: 100%;
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

.more-options {
    min-width: 42px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.chat-container {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-bottom: $border;
    cursor: pointer;

    &:hover {
        background-color: $hover-bg;
    }
}

.chat-info h5,
.chat-info p {
    margin: 0;
}

.chats-container {
    overflow-y: scroll;
    height: 100%;
}
</style>