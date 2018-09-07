/*jshint esversion: 6 */

var app = require('http').createServer();


var io = require('socket.io')(app);
var axios = require('axios');
var GameList = require('./gameList.js');



app.listen(8080, function(){
    console.log('listening on *:8080');
});

let url = 'http://165.227.163.12/api/games';
let games = new GameList();

io.on('connection', function (socket) {
    console.log('client has connected');

    // CREATE GAME
    socket.on('create_game', function (data) {
        axios.post(url, {player: data.playerName}, {
            headers: {
                Accept: 'application/json',
                key: 'secret',
            }
        })
            .then(response => {

                let game = games.createGame(response.data.id, data.playerName, data.playerId, response.data.created_at);
                socket.join(game.gameID);
                // Notifications to the client
                io.emit('lobby_changed');
            })
            .catch(error => {
                console.log(error);
            });
    });

    socket.on('get_lobby', function () {
        var lobbyGames = games.getLobbyGames();
        socket.emit('my_lobby_games', lobbyGames);
    });

    socket.on('leave_game', function (data) {
        let game = games.leaveGame(data.gameID, data.playerName);
        socket.leave(game.gameID);
        io.emit('lobby_changed');
    });

    socket.on('join_game', function (data) {
        let game = games.gameByID(data.gameID);
        axios.put(url + '/' + data.gameID + '/join', {
            playerName: data.playerName,
            team_number: game.nextTeam(),
        }, {
            headers: {
                Accept: 'application/json',
                key: 'secret'
            }
        })
            .then(response => {
                games.joinGame(data.gameID, data.playerName, data.playerId);
                socket.join(game.gameID);
                io.emit('lobby_changed');
            })
            .catch(error => {
                console.log(error);
            });


    });

    socket.on('start_game', function (data) {
        games.start(data.gameID);
        io.emit('active_games_changed');
        io.emit('lobby_changed');
    });

    socket.on('get_active_games', function (data) {
        console.log("get active games");
        let activeGames = games.getConnectedGamesOf(data.playerId);
        //cloning the array
        let clonedGames = JSON.parse(JSON.stringify(activeGames));
        clonedGames.forEach(function (game) {
            game.players.forEach(function (player) {
                player.cards.forEach(function (card) {
                    if (player.id != data.playerId && card.visible == false) {
                        card.path = 'deckdefault/semFace.png';
                        card.value = undefined;
                        card.suite = undefined;
                    }
                })
            })
        });
        console.log("jogos");
        console.log(clonedGames);
        socket.emit('active_games', clonedGames);
    });

    socket.on('play_card', function (data) {
        let game = games.gameByID(data.gameId);
        let jogou = game.playCard(data.player, data.card);
        if (jogou){
            io.to(game.gameID).emit('active_games_changed');
            if (game.checkIsGameOver()) {
                axios.put(url +'/' + game.gameID + '/end', {
                        team1_cardpoints: game.team1_cardpoints,
                        team2_cardpoints: game.team2_cardpoints,
                        team1_points: game.team1_points,
                        team2_points: game.team2_points,
                        team_winner: game.team_winner,
                        team_renunciou: game.team_renounce,
                        team_desconfiou: game.team_checkRenounce
                    },
                    {
                        headers: {
                            Accept: 'application/json',
                            key: 'secret'
                        }
                    })
                    .then(response => {
                        io.to(game.gameID).emit('game_ended', game);
                        console.log("game ended lmao");
                    })
                    .catch(function (error) {
                        console.log(error.response);
                    });
            }
        }
    });

    socket.on('report_renounce', function (data) {
        let game = games.gameByID(data.gameId);
        if(game) {
            game.reportRenounce(data.player);
            axios.put(url +'/' + game.gameID + '/end', {
                    team1_cardpoints: game.team1_cardpoints,
                    team2_cardpoints: game.team2_cardpoints,
                    team1_points: game.team1_points,
                    team2_points: game.team2_points,
                    team_winner: game.team_winner,
                    team_renunciou: game.team_renounce,
                    team_desconfiou: game.team_checkRenounce
                },
                {
                    headers: {
                        Accept: 'application/json',
                        key: 'secret'
                    }
                })
                .then(response => {
                    io.to(game.gameID).emit('renounce_confirmed', game);
                })
                .catch(function (error) {
                    console.log(error.response);
                })
            io.to(game.gameID).emit('active_games_changed');
        }
    });

    });
