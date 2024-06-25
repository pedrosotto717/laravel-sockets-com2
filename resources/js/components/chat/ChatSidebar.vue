<template>
    <aside class="chat-sidebar">
        <div class="profile-bar">
            <div class="profile-img">
                <img src="https://www.santandersmusic.com/media/magazine/shakira-disco-51-xl.webp" alt="" srcset="">
            </div>
            <div class="more-options">
                <menu-component :items="menuItems" />
                <!-- <i class="fa-solid fa-ellipsis-vertical"></i> -->
            </div>
        </div>

        <!-- <div class="chats-container">
            <div class="chat-container" v-for="chat in chats" :key="chat.id">
                <div class="profile-img">
                    <img :src="chat.image" alt="" srcset="">
                </div>
                <div class="chat-name">
                    {{ chat.name }}
                </div>
            </div>
        </div> -->
        <div class="chats-container">
            <div class="chat-container">
                <div class="profile-img">
                    <img src="https://www.santandersmusic.com/media/magazine/shakira-disco-51-xl.webp" alt="" srcset="">
                </div>
                <div class="chat-name">
                    Jose Daniel
                </div>
            </div>

            <div class="chat-container">
                <div class="profile-img">
                    <img src="https://www.santandersmusic.com/media/magazine/shakira-disco-51-xl.webp" alt="" srcset="">
                </div>
                <div class="chat-name">
                    Jose Daniel
                </div>
            </div>
        </div>

        <!-- Dialogs -->
        <add-user-dialog v-model="isAddUserDialogOpen" />
        <create-group-dialog v-model="isCreateGroupDialogOpen" :contacts="contacts"/>
        <edit-profile-dialog v-model="isEditProfileDialogOpen" :user-data="userData"/>
    </aside>
</template>

<script>
import MenuComponent from '../general/MenuComponent.vue';
import AddUserDialog from '../dialogs/AddUserDialog.vue';
import CreateGroupDialog from '../dialogs/CreateGroupDialog.vue';
import EditProfileDialog from '../dialogs/EditProfileDialog.vue';

export default {
    components: {
        MenuComponent,
        AddUserDialog,
        CreateGroupDialog,
        EditProfileDialog,
    },
    props: {
        chats: Array
    },
    data() {
        return {
            menuItems: [
                { title: 'Agregar usuario', action: this.openAddUserDialog },
                { title: 'Crear grupo', action: this.createGroup },
                { title: 'Cambiar opciones de perfil', action: this.openEditProfileDialog },
            ],
            isAddUserDialogOpen: false,
            isCreateGroupDialogOpen: false,
            isEditProfileDialogOpen: false,
            userData: {
                user_id: '1',
                username: 'Jose Daniel',
                image: 'https://www.santandersmusic.com/media/magazine/shakira-disco-51-xl.webp',
                color: '#3d86c7'
            },
            contacts: [
                { user_id: 1, username: 'Usuario 1' },
                { user_id: 2, username: 'Usuario 2' },
                { user_id: 3, username: 'Usuario 3' },
                { user_id: 1, username: 'Usuario 1' },
                { user_id: 2, username: 'Usuario 2' },
                { user_id: 3, username: 'Usuario 3' },
                { user_id: 1, username: 'Usuario 1' },
                { user_id: 2, username: 'Usuario 2' },
                { user_id: 3, username: 'Usuario 3' },
                { user_id: 1, username: 'Usuario 1' },
                { user_id: 2, username: 'Usuario 2' },
                { user_id: 3, username: 'Usuario 3' },
            ]
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
        }
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
</style>