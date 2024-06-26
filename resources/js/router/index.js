import { createRouter, createWebHistory } from "vue-router";

import LoginRegister from '../components/LoginRegister.vue';
import ChatView from '../components/ChatView.vue';
import ProfileEdit from '../components/ProfileEdit.vue';
import NotFoundPage from '../components/NotFoundPage.vue'

// Define las rutas
const routes = [
    { path: '/', component: LoginRegister },
    { path: '/login', component: LoginRegister },
    { path: '/chat', component: ChatView },
    { path: '/profile', component: ProfileEdit },
    { path: '/:pathMatch(.*)*', component: NotFoundPage }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const publicPages = ['/', '/login']; // Páginas públicas que no requieren autenticación
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = localStorage.getItem('token');

    if (authRequired && !loggedIn) {
        return next('/login');
    }

    next();
});

export default router;