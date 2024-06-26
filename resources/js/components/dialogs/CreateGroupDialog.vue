<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card class="create-group">
            <v-card-title>
                Crear grupo
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field label="Nombre del grupo" v-model="groupName" :error-messages="groupErrors" />
                    <v-text-field label="Descripción del grupo" v-model="groupDescription"
                        :error-messages="descriptionErrors" />
                    <div class="contacts-container">
                        <div v-for="contact in contacts" :key="contact.id">
                            <v-checkbox :label="contact.name" :value="contact.email"
                                :input-value="isSelected(contact.email)" @change="handleCheckboxChange(contact.contact.email)"
                                class="contact-option"></v-checkbox>
                        </div>
                    </div>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="createGroup">Crear Grupo</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { createGroup } from '@/api';

export default {
    props: {
        value: Boolean,
        contacts: Array,
        userData: Object
    },
    data() {
        return {
            groupName: '',
            groupDescription: '',
            selectedContacts: [],
            groupErrors: [],
            descriptionErrors: []
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        async createGroup() {
            const result = await createGroup(this.groupName, this.groupDescription, this.selectedContacts);
            if (result.success) {
                alert('Group created successfully');
                this.dialog = false;
                this.clearErrors();
                this.$emit('group-created');
            } else {
                this.handleErrors(result.errors);
            }
        },
        clearErrors() {
            this.groupErrors = [];
            this.descriptionErrors = [];
        },
        handleErrors(errors) {
            this.groupErrors = errors.name || [];
            this.descriptionErrors = errors.description || [];
        },
        handleCheckboxChange(email) {
            const index = this.selectedContacts.indexOf(email);
            if (index === -1) {
                this.selectedContacts.push(email);
            } else {
                this.selectedContacts.splice(index, 1);
            }
        },
        isSelected(email) {
            return this.selectedContacts.includes(email);
        }
    }
};
</script>


<style scoped lang="scss">
.contacts-container {
    max-height: 50vh;
    overflow-y: scroll;
}
</style>