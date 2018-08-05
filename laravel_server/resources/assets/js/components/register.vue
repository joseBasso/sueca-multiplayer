<template>
    <v-app>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12 class="text-xs-center">
                    <h1>Register</h1>
                </v-flex>
                <v-flex xs12 sm6 offset-sm3>
                    <form>
                        <v-layout column>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="person"
                                        name="Name"
                                        label="Full name"
                                        id="name"
                                        type="text"
                                        required></v-text-field>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="email"
                                        name="email"
                                        label="Email"
                                        id="email"
                                        type="email"
                                        required></v-text-field>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="person_outline"
                                        name="nickname"
                                        label="Nickname"
                                        id="nickname"
                                        type="text"
                                        required></v-text-field>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="lock"
                                        name="password"
                                        label="Password"
                                        id="password"
                                        type="password"
                                        required></v-text-field>
                            </v-flex>
                            <v-flex>
                                <v-text-field
                                        prepend-icon="check_circle"
                                        name="confirmPassword"
                                        label="Confirm Password"
                                        id="confirmPassword"
                                        type="password"
                                        required
                                ></v-text-field>
                            </v-flex>
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
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
