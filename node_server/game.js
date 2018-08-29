/*jshint esversion: 6 */
var axios = require('axios');
let url = 'http://sueca.proj/api/games';


class Player {
    constructor(playerNickname) {
        this.id = 0;
        this.playerNickname = playerNickname;
        this.score = 0;
        this.cards = [];
    }
}


class Card {
    constructor(value, suite, path) {
        switch(value){
            case '2':
                this.ranking = 1;
                this.points = 0;
                break;
            case '3':
                this.ranking = 2;
                this.points = 0;
                break;
            case '4':
                this.ranking = 3;
                this.points = 0;
                break;
            case '5':
                this.ranking = 4;
                this.points = 0;
                break;
            case '6':
                this.ranking = 5;
                this.points = 0;
                break;
            case '7':
                this.ranking = 9;
                this.points = 10;
                break;
            case 'Queen':
                this.ranking = 6;
                this.points = 2;
                break;
            case 'Jack':
                this.ranking = 7;
                this.points = 3;
                break;
            case 'King':
                this.ranking = 8;
                this.points = 4;
                break;
            case 'Ace':
                this.ranking = 10;
                this.points = 11;
                break;
            default:
                return null;
        }
        this.suite = suite;
        this.path = path;
        this.value = value;

    }
}


class Sueca {
    constructor(ID, player1Name, created_at) {
        this.gameID = ID;
        this.owner = player1Name;
        this.gameEnded = false;
        this.gameStarted = false;
        this.created_at  = created_at;
        this.players = [];
        this.cards = [];
        this.players[0] = new Player(player1Name);
        this.players[1] = undefined;
        this.players[2] = undefined;
        this.players[3] = undefined;
        this.joinedPlayers = 1;
        this.winner = undefined;
        this.turn = 0;
    }

}

module.exports = Sueca;
