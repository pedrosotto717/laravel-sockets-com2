<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                Cambiar opciones de perfil
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <div class="image-container">
                        <img :src="imageSrc || defaultProfile" alt="Profile Image" class="image-profile"
                            @click="triggerFileInput" />
                    </div>

                    <input type="hidden" v-model="userId" />
                    <v-text-field label="Nuevo username" v-model="username" />

                    <input type="file" ref="fileInput" style="display: none" @change="previewImage" accept="image/*" />

                    <!-- Selección de colores -->
                    <span class="color-title">Color del Tema:</span>
                    <div class="color-selection-container">
                        <div v-for="color in colors" :key="color"
                            :style="{ backgroundColor: color }"
                            :class="{ active: color === selectedColor }" @click="selectColor(color)"></div>

                        <input type="radio" v-model="selectedColor" v-for="color in colors" :key="'radio-' + color"
                            :value="color" style="display: none" />
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="saveChanges">Guardar cambios</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import defaultProfile from '@/assets/images/profile-default.jpg';
import { updateUserProfile } from '@/api/user';

export default {
    props: {
        value: Boolean,
        userData: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            defaultProfile: defaultProfile,
            userId: this.userData.user_id || '',
            username: this.userData.name || '',
            imageSrc: this.userData.profile_photo_url || '',
            selectedColor: this.userData.color_theme || '',
            colors: ['#3d86c7', '#FFD700', '#90EE90', '#ADD8E6', '#FFA07A'],
            profilePhotoFile: null,
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        async saveChanges() {
            const formData = new FormData();
            formData.append('email', this.userData.email);  // Suponiendo que email está disponible en userData
            formData.append('name', this.username);
            if (this.profilePhotoFile) {
                formData.append('profile_photo', this.profilePhotoFile);
            }
            formData.append('color_theme', this.selectedColor);

            try {
                const response = await updateUserProfile(formData);
                alert(response.message);  // Mensaje de éxito desde la API
                this.dialog = false;
                this.$emit('profile-updated');
            } catch (error) {
                alert('Error updating profile: ' + error.message);
            }
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.profilePhotoFile = file;  // Guarda el archivo para enviarlo
                const reader = new FileReader();
                reader.onload = (e) => this.imageSrc = e.target.result;
                reader.readAsDataURL(file);
            }
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        selectColor(color) {
            this.selectedColor = color;
        }
    }
};
</script>

<style scoped lang="scss">
.image-container {
    max-width: 200px;
    max-height: 200px;
    aspect-ratio: 1/1;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 16px;
}

.image-profile {
    width: 100%;
    height: 100%;
    object-fit: cover;
    cursor: pointer;
}

.color-selection-container {
    display: flex;
    align-items: center;
    gap: 8px;

    div {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
    }

    .active {
        width: 40px;
        height: 40px;
    }
}

.color-title {
    display: inline-block;
    margin-top: 16px;
    margin-bottom: 16px;
}
</style>
