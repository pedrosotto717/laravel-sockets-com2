<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                Iniciar conversación
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text>
                <v-list class="contacts-container">
                    <v-list-item v-for="contact in contacts" :key="contact.id" @click="selectContact(contact)">
                        <v-list-item-content>
                            <v-list-item-title class="option-contacts">
                                <span class="contact-data">
                                    {{ contact.contact.name }} 
                                    <span class="email">( {{ contact.contact.email }} )</span>
                                </span>
                                <span class="cta">Ir al Chat</span></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        value: Boolean,
        contacts: Array
    },
    computed: {
        dialog: {
            get() { return this.value; },
            set(val) { this.$emit('update:value', val); }
        }
    },
    methods: {
        selectContact(contact) {
            this.$emit('contact-selected', contact);
            this.dialog = false; // Cierra el diálogo después de seleccionar el contacto
        }
    }
};
</script>

<style scoped lang="scss">
@import "../../../css/styles/main";

.contacts-container {
    max-height: 50vh;
    overflow-y: scroll;
}

.option-contacts {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding-bottom: 16px;
    border-bottom: $border;
    align-items: center;
}

.cta {
    color: $main-color;
}

.contact-data {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    gap: 8px;

    .email {
        font-size: 12px;
        font-weight: 200;
    }
}
</style>