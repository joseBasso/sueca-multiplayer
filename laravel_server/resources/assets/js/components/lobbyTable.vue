<template>

    <div class="box">
        <table class="table is-striped is-hoverable">

            <thead>
            <tr>
                <th>ID</th>
                <th>Owner</th>
                <th>Created at</th>
                <th>Joined Players</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="game in games"  :key="game.gameID">
                <td>{{ game.gameID }}</td>
                <td>{{ game.owner }}</td>
                <td>{{ game.created_at }}</td>
                <td>{{ game.joinedPlayers }}/4</td>
                <td>
                    <v-btn color="success" outline small round v-if="game.owner == user && isRoomFull(game)"  @click.prevent="start(game)">Start</v-btn>
                    <v-btn color="error" outline small round v-if="alreadyJoined(game)" @click.prevent="leave(game)">Leave</v-btn>
                    <v-btn color="primary" outline small round v-if="!alreadyJoined(game)" @click.prevent="join(game)" >Join</v-btn>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script>

    module.exports={
        props: ['games', 'user'],
        data: function () {
            return {
            }
        },
        methods: {
            join: function(game){
                this.$emit('join-click', game);
            },
            leave: function(game){
                this.$emit('leave-click', game);
            },
            start: function(game){
                this.$emit('start-click', game);
            },
            alreadyJoined: function(game){
                for(let i= 0 ; i < game.joinedPlayers; i++){
                    if(game.players[i].playerNickname === this.user ){
                        return true;
                    }
                }
                return false;
            },
            isRoomFull: function(game){
                if(game.joinedPlayers == 4){
                    return true;
                }
                return false;
            }
        }
    }
</script>

<style>

</style>
