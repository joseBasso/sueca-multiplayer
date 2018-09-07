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

    createGame(gameID, playerName, playerId, created_at) {
        let game = new Sueca(gameID, playerName, playerId, created_at);
        this.games.set(game.gameID, game);
        console.log("criei um jogo\n");
        return game;
    }

    joinGame(gameID, playerName, playerId) {
        console.log(gameID + ' tem mais um jogador - ' + playerName);
        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        game.join(playerName, playerId);
        return game;
    }

    getLobbyGames() {
        let games = [];
        for (var [key, value] of this.games) {
            if ((!value.gameStarted) && (!value.gameEnded))  {
                games.push(value);
            }
        }
        return games;
    }

    getConnectedGamesOf(player) {
        let games = [];
        for (var [key, value] of this.games) {
            if(value.isInGame(player) && value.gameStarted && !(value.gameEnded)){
                games.push(value);
            }
        }
        return games;
    }

    leaveGame(gameID, playerName) {
        let game = this.gameByID(gameID);
        if (game === null) {
            return null;
        }
        game.leave(playerName);
        return game;
    }


    start(gameID) {
        let game = this.gameByID(gameID);
        if (game === null) {
            return null;
        }
        game.start();
        game.gameStarted = true;
        return game;
    }

}

module.exports = GameList;
