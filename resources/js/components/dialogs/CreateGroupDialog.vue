<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card class="create-group">
            <v-card-title>
                Crear grupo
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field label="Nombre del grupo" v-model="groupName" />
                    <div class="contacts-container">
                        <div v-for="contact in contacts" :key="contact.user_id">
                            <v-checkbox :label="contact.username" v-model="selectedContacts" class="contact-option" :value="contact.user_id"></v-checkbox>
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
export default {
    props: {
        value: Boolean,
        contacts: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            groupName: '',
            selectedContacts: []
        };
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        createGroup() {
            console.log("Grupo creado:", this.groupName, "con miembros:", this.selectedContacts);
            this.dialog = false;
        }
    }
};
</script>

<style scoped lang="scss">
    .contacts-container {
        max-height: 25vh;
        overflow-y: scroll;
    }
</style>