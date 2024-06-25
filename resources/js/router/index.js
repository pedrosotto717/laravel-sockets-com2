import { createRouter, createWebHistory } from "vue-router";

import LoginRegister from '../components/LoginRegister.vue';
import ChatView from '../components/ChatView.vue';
import ProfileEdit from '../components/ProfileEdit.vue';
import NotFoundPage from '../components/NotFoundPage.vue'

// Define las rutas
const routes = [
    { path: '/', component: LoginRegister },
    { path: '/chat', component: ChatView },
    { path: '/profile', component: ProfileEdit },
    { path: '/:pathMatch(.*)*', component: NotFoundPage }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;