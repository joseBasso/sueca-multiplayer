<template>
    <div>
        <h1 class="page-header">{{title}}</h1>
            <user-list :users="users" @user-block="blockUser" @user-delete="deleteUser"></user-list>
            <user-block v-if="blocking == true" :user="userBlocked" @user-blocked="userBlockDone"></user-block>
            <div class="alert alert-warning alert-dismissable fade in" v-if="showMessage">
                <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>{{message}}</strong>
            </div>

    </div>
</template>

<script type="text/javascript">
    import UserList from './userList.vue';
    import UserBlock from './userBlock.vue';
    export default {
        data: function(){
            return {
                title: 'Administration',
                users: [],
                blocking: false,
                userBlocked : null,
                showMessage: false,
                message: ''
            }
        },
        methods: {
            getAuthedUser(){
                axios.get('api/users/logged',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.user = response.data;
                    })
                    .catch(error=>{
                    })
            },
            getAllUsers: function(){
                axios.get('api/users',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.users = response.data;
                    })
                    .catch(error=>{
                        console.log(error);
                    })
            },
            blockUser: function(user){
                this.blocking = true;
                this.userBlocked = user;
            },
            userBlockDone: function(blockedSuccess, user){
                this.blocking = false;
                if (blockedSuccess) {
                    if(user.blocked == 0){
                        this.message = 'User unblocked';
                    }
                    else{
                        this.message = 'User blocked';
                    }
                    this.showMessage = true;
                }
                this.getAllUsers();
            },
            deleteUser: function(user){
                axios.delete('api/users/'+user.id+'/delete',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response => {
                        console.log(response);
                        this.message = 'User deleted';
                        this.showMessage = true;
                        this.getAllUsers();
                    })
                    .catch(error=>{
                        console.log(error.response);
                        this.message = error.response.message;
                        this.showMessage = true;
                    });
            }
        },
        mounted(){
            this.getAllUsers();
        },
        components: {
            'user-list' : UserList,
            'user-block' : UserBlock
        },
    }
</script>
