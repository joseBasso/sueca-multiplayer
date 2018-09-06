<template>
    <b-navbar-nav v-if="this.$root.userAuthed == false">
        <b-nav-item><router-link to="/login" tag="button">Login</router-link></b-nav-item>
        <b-nav-item><router-link to="/register" tag="button">Register</router-link></b-nav-item>
    </b-navbar-nav>

    <b-navbar-nav v-else>
        <b-nav-item><router-link to="/account" tag="button">Account</router-link></b-nav-item>
        <b-nav-item><router-link to="/statistics" tag="button">Statistics</router-link></b-nav-item>
        <b-nav-item><router-link to="/lobby" tag="button">Lobby</router-link></b-nav-item>
        <b-nav-item><router-link to="/multiplayer" tag="button">Game</router-link></b-nav-item>
        <b-nav-item @click="logoutModal = true">Logout</b-nav-item>
        <b-modal @ok="logout()" title="Logout?" ok-title="Logout" v-model="logoutModal">
            Are you sure you want to logout?
        </b-modal>
    </b-navbar-nav>



</template>

<script>
    export default {
        data: function () {
            return {
                logoutModal: false,
            }
        },
        methods: {
            logout(){
                let self = this;
                let config = {
                    headers: {
                        'Authorization': window.localStorage.getItem('access_token'),
                    }
                };
                axios.post('api/logout', null, config)
                    .then(response=>{
                        if(response.status == 200){
                            window.localStorage.removeItem('access_token');
                            console.log("Token removed from Local Storage");
                            self.$router.push('login');
                            self.$root.userAuthed = false;
                        }
                    }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>
