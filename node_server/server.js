/*jshint esversion: 6 */

var app = require('http').createServer();


var io = require('socket.io')(app);
var axios = require('axios');
var GameList = require('./gameList.js');



app.listen(8080, function(){
    console.log('listening on *:8080');
});

let url = 'http://sueca.proj/api/games';
let games = new GameList();

io.on('connection', function (socket) {
    console.log('client has connected');

    // CREATE GAME
    socket.on('create_game', function (data){
        axios.post(url, { player: data.playerName}, {
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

    socket.on('get_lobby', function (){
        var lobbyGames = games.getLobbyGames();
        socket.emit('my_lobby_games', lobbyGames);
    });

    socket.on('leave_game', function (data){
        let game = games.leaveGame(data.gameID, data.playerName);
        socket.leave(game.gameID);
        io.emit('lobby_changed');
    });

    socket.on('join_game', function (data){
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

    socket.on('start_game', function (data){
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
                player.cards.forEach(function (card){
                    if (player.id != data.playerId && card.visible == false) {
                        card.path = 'deckdefault/semFace.png';
                        card.value = undefined;
                        card.suite = undefined;
                    }
                })
            })
        });
        socket.emit('active_games', clonedGames);
    });

});
