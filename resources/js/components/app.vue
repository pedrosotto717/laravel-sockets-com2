<template>
    <nav>
        <div>
            <router-link to="/">Login/Register</router-link>
            <br>
            <router-link to="/chat">Chat</router-link>
            <br>
            <router-link to="/profile">Profile</router-link>
        </div>
    </nav>

    <main class="d-flex">
        <router-view/>
    </main>
</template>

<style scoped lang="scss">
    @import "../../css/styles/main";

    main {
        display: flex;
        flex-wrap: wrap;
    }
</style>