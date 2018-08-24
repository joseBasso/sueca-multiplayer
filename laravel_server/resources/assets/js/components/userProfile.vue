<template>
    <v-app>
        <v-container>
            <v-layout column>
                <v-text-field
                        name="email"
                        label="Email"
                        id="email"
                        type="email"
                        :readonly="editing == false"
                        :rules="emailRules"
                        v-model="user.email"
                        required></v-text-field>
                <v-text-field
                        name="nickname"
                        label="Nickname"
                        id="nickname"
                        type="text"
                        :readonly="editing == false"
                        v-model="user.nickname"
                        :rules="nicknameRules"
                        required></v-text-field>
                <v-text-field
                        name="name"
                        label="Name"
                        id="name"
                        type="text"
                        :readonly="editing == false"
                        v-model="user.name"
                        required></v-text-field>

                <v-flex v-if="editing">
                    <v-text-field
                            name="oldPassword"
                            label="Old Password"
                            id="oldPassword"
                            type="password"
                            v-model="user.oldPassword"
                            required></v-text-field>
                    <v-text-field
                            name="password"
                            label="Password"
                            id="password"
                            type="password"
                            v-model="user.password"
                            required></v-text-field>
                    <span class="red--text" v-if="showError && errorMessages['password']"> {{errorMessages['password'][0]}} </span>
                    <v-text-field
                            name="confirmPassword"
                            label="Confirm Password"
                            id="confirmPassword"
                            type="password"
                            :rules="passwordRules"
                            v-model="user.password_confirmation"
                            required
                    ></v-text-field>
                    <v-btn block color="secundary" dark @click.native.stop="saveUser()">Save Changes</v-btn>
                </v-flex>
                <v-btn
                        absolute
                        small
                        dark
                        fab
                        right
                        :outline="editing"
                        color="blue"
                        @click.native.stop="editing = !editing">
                    <v-icon>edit</v-icon>
                </v-btn>
            </v-layout>
        </v-container>

        <v-snackbar
                v-model="snackbar"
                :color="messageColor">
            {{ message }}
            <v-btn
                    dark
                    flat
                    @click="snackbar = false"
            >
                Close
            </v-btn>
        </v-snackbar>
    </v-app>
</template>

<script>
    export default {
        data: function () {
            return {
                user: {
                    nickname: null,
                    email: null,
                    name: null,
                    oldPassword: null,
                    password: null,
                    password_confirmation: null
                },
                message: null,
                messageColor: null,
                snackbar: false,
                editing: false,
                showError: false,
                errorMessages: null,
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
            getAuthedUser(){
                axios.get('api/user',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('access_token'),
                    }
                })
                    .then(response=>{
                        this.user = response.data;
                    })
                    .catch(error=>{
                        console.log("Couldn't Retrieve User");
                    })
            },
            saveUser: function(){
                axios.put('api/users/'+this.user.id, this.user, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('access_token'),
                    }
                })
                    .then(response=>{
                        this.editing = false;
                        this.messageColor="info";
                        this.message = "User edited";
                        this.snackbar = true;

                    })
                    .catch(error=>{
                        this.editing = true;
                        this.messageColor="error";
                        this.message = error.response.data.message;
                        this.snackbar = true;
                    });
            }
        },
        mounted(){
            this.getAuthedUser();
        }
    }
</script>
