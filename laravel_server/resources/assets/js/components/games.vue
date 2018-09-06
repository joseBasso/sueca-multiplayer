<template>
    <v-app>
        <v-container>
            <h1>Gaming Area</h1>
            <h3 v-if="activeGames.length < 1">You have no active games. Go to Lobby and create/join one</h3>
            <v-container class="elevation-4" v-for="game in activeGames" :key="game.gameID">
                <h4>Game:</h4>
                <hr>
                <h5>Team 1: {{ game.team1_cardpoints }} points </h5>
                <h5>Team 2: {{ game.team2_cardpoints }} points </h5>
                <h6 class="text-center">Trump</h6>
                <div class="text-center">
                    <img v-bind:src="'storage/' + base_path + game.cardTrump.path" height="150">
                </div>

                <v-container grid-list>
                    <v-layout row wrap>
                        <v-flex>
                            <v-container class="elevation-1">
                                <h6>Team 1</h6>
                                <hr>

                                <div class="text-center" v-if="game.players[0].playerNickname != currentPlayer.nickname"> <strong>{{game.players[0].playerNickname}}</strong> cards:
                                    <br>
                                    <img v-for="(card,index) in game.players[0].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[0].cards" v-bind:src="'storage/' + base_path + card.path" height="90" @click="clicked">
                                    </div>
                                </v-card>

                                <div class="text-center" v-if="game.players[2].playerNickname != currentPlayer.nickname"> <strong>{{game.players[2].playerNickname}}</strong> cards:
                                    <br>
                                    <img v-for="(card,index) in game.players[2].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[2].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                    </div>
                                </v-card>

                            </v-container>
                            <br/>
                            <v-container class="elevation-1">
                                <h6>Team 2</h6>
                                <hr>

                                <div class="text-center" v-if="game.players[1].playerNickname != currentPlayer.nickname"> <strong>{{game.players[1].playerNickname}}</strong> cards:
                                    <br>
                                    <img  v-for="(card,index) in game.players[1].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[1].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                    </div>
                                </v-card>

                                <div class="text-center" v-if="game.players[3].playerNickname != currentPlayer.nickname"> <strong>{{game.players[3].playerNickname}}</strong> cards:
                                    <br>
                                    <img v-for="(card,index) in game.players[3].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[3].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                    </div>
                                </v-card>

                            </v-container>

                            <v-container>
                                <h4>Board</h4>
                                <h6>It's {{game.players[game.turn-1].playerNickname}} Turn!!</h6>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>

            </v-container>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        data: function () {
            return {
                activeGames: [],
                currentPlayer: null,
                base_path: '',
            }
        },
        sockets: {
            active_games(games){
                console.log(games);
                this.activeGames = games;
            },
        },
        methods: {
            clicked: function(){
              console.log("clicked");
            },
            getAuthedUser: function(){
                axios.get('api/user',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('access_token'),
                    }
                })
                    .then(response=>{
                        this.currentPlayer = response.data;
                        this.getActiveGames();
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
            getActiveGames: function(){
                this.$socket.emit('get_active_games', { playerId: this.currentPlayer.id });
            },
            getBasePath: function(){
                axios.get('api/decks/path',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('access_token'),
                    }
                })
                    .then(response=>{
                        this.base_path = response.data;
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
        },
        mounted(){
            this.getAuthedUser();
            this.getBasePath();
        }
    }
</script>

<style scoped>
    .card:hover {
        cursor: pointer;
    }
</style>