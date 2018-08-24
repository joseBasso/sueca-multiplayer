<template>
    <v-app>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12 class="text-xs-center">
                    <h1>Register</h1>
                </v-flex>
                <v-flex xs12 sm6 offset-sm3>
                    <v-form @submit.prevent="register(user)">
                        <v-layout column>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="person"
                                        name="Name"
                                        label="Full name"
                                        id="name"
                                        type="text"
                                        v-model="user.name"
                                        required></v-text-field>
                                <span class="red--text" v-if="showError && errorMessages['name']"> {{errorMessages['name'][0]}} </span>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="email"
                                        name="email"
                                        label="Email"
                                        id="email"
                                        type="email"
                                        :rules="emailRules"
                                        v-model="user.email"
                                        required></v-text-field>
                                <span class="red--text" v-if="showError && errorMessages['email']"> {{errorMessages['email'][0]}} </span>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="person_outline"
                                        name="nickname"
                                        label="Nickname"
                                        id="nickname"
                                        type="text"
                                        :rules="nicknameRules"
                                        :counter="15"
                                        v-model="user.nickname"
                                        required></v-text-field>
                                <span class="red--text" v-if="showError && errorMessages['nickname']"> {{errorMessages['nickname'][0]}} </span>
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
                                <span class="red--text" v-if="showError && errorMessages['password']"> {{errorMessages['password'][0]}} </span>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="check_circle"
                                        name="confirmPassword"
                                        label="Confirm Password"
                                        id="confirmPassword"
                                        type="password"
                                        :rules="passwordRules"
                                        v-model="password_confirmation"
                                        required
                                ></v-text-field>
                            </v-flex>

                            <v-flex class="text-xs-center" mt-2>
                                <v-btn dark type="submit">Register</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-form>
                </v-flex>
            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        data: function () {
            return {
                showError: false,
                errorMessages: null,
                user: {
                    email: null,
                    password: null,
                    name:null,
                    nickname:null
                },
                password_confirmation: null,
                emailRules: [
                    v => !!v || 'Email is required',
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
                ],
                nicknameRules: [
                    v => !!v || 'Nickname is required',
                    v => v.length <= 15 || 'Nickname must be less than 15 characters'

                ],
                passwordRules: [
                    v => v == this.user.password  || 'Password confirmation must match password'
                ]
            }
        },
        methods: {
            register(user) {
                let self = this;
                axios.post('/api/register', {email: user.email, password: user.password, name: user.name, nickname: user.nickname})
                    .then(function(response){
                        self.$router.push('login');
                        this.showError = false;
                    }).catch(error => {
                    this.showError = true;
                    this.errorMessages = error.response.data.errors;
                });

            }
        }
    }
</script>
