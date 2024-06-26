<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card class="block-user">
            <v-card-title>
                {{ action === 'block' ? 'Eliminar usuario' : 'Salir del Grupo' }}
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    ¿Estás seguro de que deseas {{ action === 'block' ? 'eliminar a este usuario' : 'salir de este grupo' }}?
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" @click="performAction">Sí</v-btn>
                <v-btn color="red" @click="closeDialog">No</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { deleteContact, leaveGroup } from '@/api';

export default {
    props: {
        value: Boolean,
        action: String, // 'block' o 'leave'
        itemId: [String, Number] // Id del contacto o del grupo
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        async performAction() {
            try {
                if (this.action === 'block') {
                    await deleteContact(this.itemId); // Asumiendo que contactEmail es una prop o está definido en data
                    alert("Contacto bloqueado con éxito");
                } else if (this.action === 'leave') {
                    await leaveGroup(this.itemId); // Asumiendo que groupId está disponible
                    alert("Has salido del grupo con éxito");
                }
                this.$emit('block-action'); // Emitir un evento para refrescar el listado o ajustar la UI
                this.dialog = false;
            } catch (error) {
                alert('Error al realizar la acción: ' + error.message);
            }
        },
        closeDialog() {
            this.dialog = false;
        }
    }
};
</script>

<style scoped>
.block-user {
    .v-card-title,
    .v-card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
}
</style>