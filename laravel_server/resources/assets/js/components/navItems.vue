<template>

    <b-navbar-nav v-if="this.$root.userAuthed == false">
        <b-nav-item><router-link to="/login" tag="button">Login</router-link></b-nav-item>
        <b-nav-item><router-link to="/register" tag="button">Register</router-link></b-nav-item>
    </b-navbar-nav>

    <b-navbar-nav v-else>
        <b-nav-item><router-link to="/account" tag="button">Account</router-link></b-nav-item>
        <b-nav-item><router-link to="/statistics" tag="button">Statistics</router-link></b-nav-item>
        <b-nav-item><router-link to="/lobby" tag="button">Lobby</router-link></b-nav-item>
        <b-nav-item href="#">Game</b-nav-item>
    </b-navbar-nav>

</template>

<script>
    export default {
    }
</script>
