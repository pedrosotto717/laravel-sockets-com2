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
                        <img :src="userData.profile_photo_url || defaultProfile" alt="Profile Image" class="image-profile" @click="triggerFileInput" />
                    </div>

                    <input type="hidden" v-model="userId" />
                    <v-text-field label="Nuevo username" v-model="username" />
                    
                    <input type="file" ref="fileInput" style="display: none" @change="previewImage" accept="image/*" />
                    
                    <!-- Selección de colores -->
                    <span class="color-title">Color del Tema:</span>
                    <div class="color-selection-container">
                        <div v-for="color in colors" :key="color"
                            :style="{ backgroundColor: color, width: '30px', height: '30px', borderRadius: '50%', cursor: 'pointer' }"
                            @click="selectColor(color)"></div>

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
            username: this.userData.username || '',
            imageSrc: this.userData.image || '',
            selectedColor: this.userData.color || '',
            colors: ['#3d86c7', '#FFD700', '#90EE90', '#ADD8E6', '#FFA07A'],
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        saveChanges() {
            console.log("Cambios guardados:", this.userId, this.username, this.imageSrc, this.selectedColor);
            this.dialog = false;
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
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
    }

    .color-selection-container {
        display: flex;
        gap: 8px;
    }

    .color-title {
        display: inline-block;
        margin-top: 16px;
        margin-bottom: 16px;
    }
</style>
