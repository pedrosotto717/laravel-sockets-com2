<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                Agregar contacto
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field label="Email del contacto" v-model="email" :error-messages="emailErrors" />
                    <v-text-field label="Nombre del contacto" v-model="name" :error-messages="nameErrors" />
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
import { addContact } from '@/api/contacts';

export default {
    props: {
        value: Boolean
    },
    data() {
        return {
            email: '',
            name: '',
            emailErrors: [],
            nameErrors: [],
            generalError: ''
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        async addUser() {
            const result = await addContact(this.email, this.name);
            if (result.success) {
                alert('Contact created successfully');
                this.$emit('contact-added');
                this.dialog = false; // Close the dialog
                this.clearErrors();
            } else {
                this.handleErrors(result.errors);
            }
        },
        clearErrors() {
            this.emailErrors = [];
            this.nameErrors = [];
            this.generalError = '';
        },
        handleErrors(errors) {
            this.emailErrors = errors.email || [];
            this.nameErrors = errors.name || [];
            this.generalError = errors.general ? errors.general.join(' ') : '';
        }
    }
};
</script>