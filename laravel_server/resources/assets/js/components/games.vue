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

                <v-container>
                    <h4>Board</h4>
                    <h6>It's {{game.players[game.turn-1].playerNickname}} Turn!!</h6>

                    <div>
                        <img v-for="(card,index) in game.boardCards" v-bind:src="'storage/' + base_path + card.path" height="100">
                    </div>
                </v-container>

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
                                        <img v-for="(card,index) in game.players[0].cards" v-bind:src="'storage/' + base_path + card.path" height="90" @click="clicked(game, card)">
                                    </div>
                                </v-card>

                                <div class="text-center" v-if="game.players[2].playerNickname != currentPlayer.nickname"> <strong>{{game.players[2].playerNickname}}</strong> cards:
                                    <br>
                                    <img v-for="(card,index) in game.players[2].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[2].cards" v-bind:src="'storage/' + base_path + card.path" height="90" @click="clicked(game, card)">
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
                                        <img v-for="(card,index) in game.players[1].cards" v-bind:src="'storage/' + base_path + card.path" height="90" @click="clicked(game, card)">
                                    </div>
                                </v-card>

                                <div class="text-center" v-if="game.players[3].playerNickname != currentPlayer.nickname"> <strong>{{game.players[3].playerNickname}}</strong> cards:
                                    <br>
                                    <img v-for="(card,index) in game.players[3].cards" v-bind:src="'storage/' + base_path + card.path" height="90">
                                </div>

                                <v-card v-else dark color="green" class="card">
                                    <v-card-text>Your cards</v-card-text>
                                    <div class="text-center">
                                        <img v-for="(card,index) in game.players[3].cards" v-bind:src="'storage/' + base_path + card.path" height="90" @click="clicked(game, card)">
                                    </div>
                                </v-card>

                            </v-container>


                        </v-flex>
                    </v-layout>
                </v-container>

            </v-container>

            <v-snackbar v-model="showMessage" type="info" outline>
                {{message}}
                <v-btn dark flat @click="showMessage = false">
                    Close
                </v-btn>
            </v-snackbar>

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
                message: '',
                showMessage: ''
            }
        },
        sockets: {
            active_games(games){
                console.log("game received");
                console.log(games);
                this.activeGames = games;
            },
            active_games_changed(){
                this.getActiveGames();
                console.log("games changed");
            },
            game_ended(game){
                let auxGame = game;
                let playerIndex = auxGame.players.findIndex(p => p.id === currentPlayer.id);
                let playerTeam = 0;
                if (playerIndex == 0 || playerIndex == 2){
                    playerTeam = 1;
                } else {
                    playerTeam = 2;
                }

                if (playerTeam == game.team_winner){
                    this.message = 'You won a game!'
                } else {
                    this.message = 'You lost a game.'
                }
                this.showMessage = true;
            }
        },
        methods: {
            clicked: function(game, card){
              console.log("cliquei numa carta");
              if (!game.gameEnded){
                  this.$socket.emit('play_card', {gameId: game.gameID, player: this.currentPlayer.id, card: card});
              }
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