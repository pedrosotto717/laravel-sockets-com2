<template>
    <div class="login-register">
        <h1>{{ isLoginMode ? 'Inicia Sesión' : 'Regístrate' }}</h1>
        <form @submit.prevent="handleSubmit" autocomplete="off">

            <div class="input-container" v-if="!isLoginMode">
                <label for="login-name">Name:</label>
                <input v-model="user.name" type="text" id="login-name" placeholder="Name" required>
            </div>

            <div class="input-container">
                <label for="login-email">Email:</label>
                <input v-model="user.email" type="email" id="login-email" placeholder="Email" required>
            </div>

            <div class="input-container">
                <label for="login-password">Password:</label>
                <input v-model="user.password" type="password" id="login-password" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="main-button">{{ isLoginMode ? 'Inicia Sesión' : 'Regístrate' }}</button>
            <button type="button" @click="toggleMode">{{ isLoginMode ? '¿Necesitas una cuenta?' : '¿Ya tienes cuenta?' }}</button>
        </form>
    </div>
</template>
  
<script>
import { login, register } from '../api';

export default {
    data() {
        return {
        user: {
            email: '',
            password: '',
            name: ''
        },
        isLoginMode: true
        };
    },
    methods: {
        async handleSubmit() {
            try {
                const response = this.isLoginMode ? await login(this.user) : await register(this.user);
                alert(response.message); // Mensaje de éxito
                this.$router.push('/chat'); // Redireccionamos al chat
            } catch (error) {
                console.log(error)
                alert(error); // Mensaje de error desde la API
            }
        },
        toggleMode() {
            this.isLoginMode = !this.isLoginMode;
        }
    }
};
</script>
  
<style scoped lang="scss">
    @import "../../css/styles/main";

    .login-register {
        width: 25%;
        margin: 0 auto;
        background-color: $app-bg;
        padding: 32px 16px;
        border-radius: 16px;
    }

    .login-register form {
        display: flex;
        flex-direction: column;
    }
</style>