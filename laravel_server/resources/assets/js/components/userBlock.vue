<template>
    <div class="jumbotron">
        <div v-if="!user.blocked">
            <h3>Blocking {{user.nickname}}</h3>
            <form>
                <div class="form-group">
                    <label for="blockReason">Reason</label>
                    <input type="text" name="reason" id="blockReason" class="form-control" v-model="user.reason_blocked">
                </div>
                <div class="col-3">
                    <v-btn color="red" @click.native.prevent="toggleBlockUser()">Block</v-btn>
                    <v-btn color="grey" @click.native.prevent="cancelBlock()">Cancel</v-btn>
                </div>
            </form>
        </div>
        <div v-else>
            <h3>Unblocking {{user.nickname}}</h3>
            <form>
                <div class="form-group">
                    <label for="unblockReason">Reason</label>
                    <input type="text" name="reason" id="unblockReason" class="form-control" v-model="user.reason_reactivated">
                </div>
                <div class="col-3">
                    <v-btn color="red" @click.native.prevent="toggleBlockUser()">Unblock</v-btn>
                    <v-btn @click.native.prevent="cancelBlock()">Cancel</v-btn>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    module.exports={
        props: ['user'],
        methods: {
            toggleBlockUser: function(){
                axios.put('api/users/'+this.user.id+'/block', this.user, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token')
                    }
                })
                    .then(response=>{
                        if (this.user.blocked == 0) {
                            this.user.blocked = 1;
                        }
                        else{
                            this.user.blocked = 0;
                        }
                        this.$emit('user-blocked', true, this.user);
                    })
                    .catch(error=>{
                    });
            },
            cancelBlock: function(){
                this.$emit('user-blocked', false, this.user);
            },
        }
    }
</script>