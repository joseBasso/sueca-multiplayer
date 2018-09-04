<template>
    <v-app>
        <v-container>
            <h1 class="page-header">{{title}}</h1>
            <h2>Current Email - <kbd>{{platformEmail}}</kbd></h2>
            <form @submit.prevent="updatePlatformEmail()">
                <v-flex>
                    <v-text-field
                            required
                            name="email"
                            label="Platform Email"
                            id="email"
                            type="email"
                            v-model="platformEmail"
                            ></v-text-field>
                </v-flex>
                <v-flex>
                    <v-text-field
                            required
                            name="password"
                            label="Password"
                            id="password"
                            type="password"
                            v-model="platformEmailPassword"
                            ></v-text-field>
                </v-flex>
                <v-btn color="primary" type="submit">Save</v-btn>
            </form>
        </v-container>

        <v-snackbar v-model="showInfo" type="info" outline>
            {{infoMessage}}
            <v-btn dark flat @click="showInfo = false">
                Close
            </v-btn>
        </v-snackbar>

    </v-app>
</template>

<script type="text/javascript">
    export default {
        data: function(){
            return {
                title: 'Platform Email',
                platformEmail: '',
                platformEmailPassword: '',
                showInfo: false,
                infoMessage:''
            }
        },
        methods: {
            getPlatformEmail(){
                axios.get('api/platform/email',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.platformEmail = response.data;
                    })
                    .catch(error=>{
                        console.log(error.response);
                        this.platformEmail  = 'Error finding Email';
                    })
            },
            updatePlatformEmail(){
                axios.put('api/platform/email' , {email: this.platformEmail, password: this.platformEmailPassword}, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.showInfo = true;
                        this.infoMessage = response.data;
                    })
                    .catch(error=>{
                        this.showInfo = true;
                        this.infoMessage = "Error Updating Email";
                        console.log(error.response);
                    });
            }
        },
        mounted(){
            this.getPlatformEmail();
        },
    }
</script>
