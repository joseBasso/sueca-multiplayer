<template>
    <v-app>
        <h2 >Current Player : {{ currentPlayer }}</h2>
        <div class="text-xs-center">
            <v-btn round dark  @click.prevent="createGame">Create Game</v-btn>
        </div>
        <hr>
        <h4 class="text-center">Pending games</h4>

        <lobby-table :games="lobbyGames" :user="currentPlayer" @join-click="join" @leave-click="leave" @start-click="start"></lobby-table>
</v-app>
</template>

<script>
    import LobbyTable from './lobbyTable.vue';

    export default {

        data: function () {
            return {
                currentPlayer: '',
                socketId: '',
                lobbyGames: [],
            }
        },
        sockets: {
            connect(){
                console.log('socket connected');
                this.socketId = this.$socket.id;
            },
            disconnect(){
                console.log('socket disconnected');
                this.socketId = "";
            },
            lobby_changed(){
                this.loadLobby();
            },
            my_lobby_games(games){
                this.lobbyGames = games;
            },
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
                        this.currentPlayer = response.data.nickname;
                    })
                    .catch(error=>{
                        warnMessage(error.response.data.msg);
                    })
            },

            createGame: function(){
                this.$socket.emit('create_game', { playerName: this.currentPlayer });
            },

            loadLobby(){
                this.$socket.emit('get_lobby');
            },
            join: function(game){
                this.$socket.emit('join_game', {gameID: game.gameID, playerName: this.currentPlayer });
            },
            leave: function(game){
                this.$socket.emit('leave_game', {gameID: game.gameID, playerName: this.currentPlayer });
            },
            start: function(game){
                this.$socket.emit('start_game', {gameID: game.gameID, playerName: this.currentPlayer });
            }
        },
        components: {
            'lobby-table': LobbyTable,
        },
        mounted() {
            this.getAuthedUser();
            this.loadLobby();
        }

    }
</script>
