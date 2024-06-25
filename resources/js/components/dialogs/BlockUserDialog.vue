<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card class="block-user">
            <v-card-title>
                Bloquear usuario
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    ¿Estás seguro de que deseas bloquear a este usuario?
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green" @click="blockUser">Sí</v-btn>
                <v-btn color="red" @click="closeDialog">No</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
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
        blockUser() {
            console.log("Usuario bloqueado");
            this.dialog = false;
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
