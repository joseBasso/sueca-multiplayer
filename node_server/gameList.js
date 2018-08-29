/*jshint esversion: 6 */

var Sueca = require('./game.js');

class GameList {

    constructor() {
        this.contadorID = 0;
        this.games = new Map();
    }

    gameByID(gameID) {
        let game = this.games.get(gameID);
        return game;
    }

    createGame(gameID, playerName, created_at) {
        var game = new Sueca(gameID, playerName, created_at);
        this.games.set(game.gameID, game);
        console.log("criei um jogo\n");
        console.log(game);
        return game;
    }

}

module.exports = GameList;
