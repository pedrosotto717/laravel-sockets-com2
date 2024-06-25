<!-- MenuComponent.vue -->
<template>
    <v-menu>
        <template v-slot:activator="{ props }">
            <v-btn icon v-bind="props">
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
  
        <v-list >
            <v-list-item 
                v-for="(item, index) in items" 
                :key="index" 
                @click="item.action"
                class="option-item"
            >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
  
<script>
export default {
    props: {
        items: {
            type: Array,
            required: true
        }
    }
};
</script>

<style scoped lang="scss">
    @import "../../../css/styles/main";

    button {
        background: transparent;
        border-radius: 50%;
        border: none;
        box-shadow: none;
        transition: 1s ease all;
    }

    .option-item {
        cursor: pointer;
        &:hover {
            background-color: $hover-bg !important;
        }
    }
</style>
  