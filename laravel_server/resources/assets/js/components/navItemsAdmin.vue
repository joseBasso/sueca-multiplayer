<template>
    <b-navbar-nav v-if="this.$root.adminAuthed == false">
        <b-nav-item><router-link to="/welcome" tag="button">Login</router-link></b-nav-item>
    </b-navbar-nav>

    <b-navbar-nav v-else>
        <b-nav-item><router-link to="/administration" tag="button">Administration</router-link></b-nav-item>
        <b-nav-item><router-link to="/platform" tag="button">Platform Email</router-link></b-nav-item>
        <b-nav-item><router-link to="/decks" tag="button">Deck Management</router-link></b-nav-item>
        <b-nav-item><router-link to="/statistics" tag="button">Statistics</router-link></b-nav-item>

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
                        'Authorization': window.localStorage.getItem('admin_access_token'),
                    }
                };
                axios.post('api/logout', null, config)
                    .then(response=>{
                        if(response.status == 200){
                            window.localStorage.removeItem('access_token');
                            console.log("Token removed from Local Storage");
                            self.$router.push('login');
                            self.$root.adminAuthed = false;
                        }
                    }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>