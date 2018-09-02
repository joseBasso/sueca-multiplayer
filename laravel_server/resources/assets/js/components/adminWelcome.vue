<template>
    <v-app>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12 class="text-xs-center">
                    <img src="https://vignette.wikia.nocookie.net/daria/images/1/15/Admin.png/revision/latest?cb=20140902104042"/>
                    <br/>
                    <h2>Welcome to Sueca Online administration page. Proceed with caution</h2>
                    <br/>
                    <h3>If you are an administrator, please login. If you are not, there is nothing here for you</h3>
                </v-flex>
                <v-flex xs12 sm6 offset-sm3 mt-3>
                    <form @submit.prevent="login(user)">
                        <v-layout column>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="person"
                                        name="email"
                                        label="Email"
                                        id="email"
                                        type="email"
                                        v-model="user.email"
                                        required></v-text-field>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="lock"
                                        name="password"
                                        label="Password"
                                        id="password"
                                        type="password"
                                        v-model="user.password"
                                        required></v-text-field>
                            </v-flex>

                            <v-alert :value="showError" type="error" outline>
                                {{errorMessage}}
                            </v-alert>

                            <v-flex class="text-xs-center" mt-2>
                                <v-btn dark type="submit">Login</v-btn>
                            </v-flex>
                        </v-layout>
                    </form>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>

    export default {
        data: function () {
            return {
                user: {
                    email: null,
                    password: null,
                },
                showError: false,
                errorMessage: '',
            }
        },
        methods: {
            login:function(user){
                let self = this;
                axios.post('/api/login', { email: user.email, password: user.password })
                    .then(function(response){
                        window.localStorage.setItem("admin_access_token", response.data.token_type + ' ' + response.data.access_token);
                        self.showError = false;
                        self.$root.adminAuthed = true;
                        self.$router.push('administration');
                    }).catch(error => {
                    this.showError = true;
                    this.errorMessage = error.response.data.message;
                });
            },
        },
    }
</script>