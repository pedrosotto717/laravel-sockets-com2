<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                Confirmar Cierre de Sesión
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                ¿Estás seguro de que deseas cerrar sesión?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" @click="confirmLogout">Sí</v-btn>
                <v-btn color="red" @click="closeDialog">No</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { logout } from '@/api/auth';

export default {
    props: {
        value: Boolean
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        async confirmLogout() {
            try {
                await logout();
                this.$router.replace('/'); // Redirige al usuario a la página de inicio
                this.dialog = false;
            } catch (error) {
                alert('Error: ' + error);
            }
        },
        closeDialog() {
            this.dialog = false;
        }
    }
};
</script>
