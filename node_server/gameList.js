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
        let game = new Sueca(gameID, playerName, created_at);
        this.games.set(game.gameID, game);
        console.log("criei um jogo\n");
        return game;
    }

    joinGame(gameID, playerName) {
        console.log(gameID + ' tem mais um jogador - ' + playerName);
        let game = this.gameByID(gameID);
        if (game===null) {
            return null;
        }
        game.join(playerName);
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

    getConnectedGamesOf(playerName) {
        let games = [];
        for (var [key, value] of this.games) {
            if(value.isInGame(playerName) && value.gameStarted){
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
        return game;
    }

}

module.exports = GameList;
