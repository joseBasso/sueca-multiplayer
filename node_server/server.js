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
        console.log(data);
        axios.post(url, { player: data.playerName}, {
            headers: {
                Accept: 'application/json',
                key: 'secret',
            }
        })
            .then(response => {

                let game = games.createGame(response.data.id, data.playerName, response.data.created_at);
                socket.join(game.gameID);
                // Notifications to the client
                //sio.emit('lobby_changed');
            })
            .catch(error => {
                console.log(error);
            });
    });

});
