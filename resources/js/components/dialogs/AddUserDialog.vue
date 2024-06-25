<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                Agregar usuario
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field label="Email del usuario" v-model="email" />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="addUser">Agregar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>


<script>
export default {
    props: {
        value: Boolean
    },
    data() {
        return {
            email: ''
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        addUser() {
            // Aquí iría la lógica para agregar al usuario
            console.log("Usuario agregado:", this.email);
            this.dialog = false;
        }
    }
};
</script>