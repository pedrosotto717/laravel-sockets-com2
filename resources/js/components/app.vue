<template>
    <main class="d-flex">
        <router-view/>
    </main>
</template>

<style scoped lang="scss">
    @import "../../css/styles/main";

    main {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
    }
</style>