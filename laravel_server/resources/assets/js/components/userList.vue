<template>
    <v-app>
    <div id="userList">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Nick</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users" :key="user.id" >
                <td>{{ user.id  }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.nickname }}</td>
                <td>
                    <v-btn color="black" outline small round  @click.native="blockUser(user)">
                        <div v-if="user.blocked==1">
                            Unblock
                        </div>
                        <div v-else>
                            Block
                        </div>
                    </v-btn>
                    <v-btn color="red" outline small round @click.native="deleteUser(user)">Delete</v-btn>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </v-app>
</template>
<script type="text/javascript">
    module.exports={
        props:['users'],
        data: function () {
            return {
            }
        },
        methods: {
            deleteUser: function(user){
                this.$emit('user-delete', user);
            },
            blockUser: function(user){
                this.$emit('user-block', user);
            }
        }
    }
</script>
