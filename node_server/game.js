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

    join(playerNickname){
        this.players[this.joinedPlayers] = new Player(playerNickname);
        this.players[this.joinedPlayers].id = this.joinedPlayers;
        this.joinedPlayers++;
    }

    leave(playerName) {
        if (this.owner == playerName) {
            this.cancelGame();
            this.gameEnded = true;
        }else{
            for (var i = 0; i < this.joinedPlayers; i++) {
                if (this.players[i].playerNickname == playerName) {
                    this.players[i].playerNickname = undefined;
                    this.joinedPlayers--;

                    this.removePlayerFromGame(playerName);

                }
            }
        }
    }

    removePlayerFromGame(playerName){
        axios.put(url + '/' + this.gameID + '/leave', {
            playerName: playerName
        }, {
            headers: {
                Accept: 'application/json',
                key: 'secret'
            }
        })
            .then(response => {
            })
            .catch(error => {
                console.log(error);
            });

    }

    cancelGame(){
        axios.get(url + '/' + this.gameID + '/cancel',{
            headers: {
                Accept: 'application/json',
                key: 'secret',
            }
        })
            .then(response => {
            })
            .catch(error => {
                console.log(error);
            });

    }

    nextTeam(){
        return this.joinedPlayers % 2 == 0 ? 1 : 2;
    }

    start() {
        if (!this.gameEnded && this.joinedPlayers == 4) {
            axios.put(url + '/' + this.gameID + '/start', null, {
                headers: {
                    Accept: 'application/json',
                    key: 'secret',
                }
            })
                .then(response => {
                    this.gameStarted = true;
                    this.generateDeck();
                    console.log(this.cards);
                })
                .catch(error => {
                    console.log(error);
                });

        }
    }

    generateDeck(){
        let deckCards;
        axios.get('http://sueca.proj/api/decks/1', {
            headers: {
                Accept: 'application/json',
            }
        })
            .then(response=>{
                deckCards = response.data;
                this.initializeDeck(deckCards);
                this.shuffleDeck();
                this.distributeCards();
            })
            .catch(error=>{
                console.log(error);
            });
    }

    initializeDeck(deckCards){
        for (var i = 0; i < 40; i++) {
            var card = new Card(deckCards[i].value, deckCards[i].suite, deckCards[i].path);
            this.cards.push(card);
        }
    }

    shuffleDeck(){
        var j, x, i;
        for (i = this.cards.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = this.cards[i];
            this.cards[i] = this.cards[j];
            this.cards[j] = x;
        }
    }

    distributeCards(){
        var cardsDealt = 0;
        for (var i = 0; i < 4; i++) {
            for (var j = 0; j < 10; j++) {
                this.players[i].cards.push(this.cards[cardsDealt + j]);
            }
            cardsDealt = cardsDealt + 10;
        }
    }

    isInGame(playerName) {
        for (let i = 0; i < 4; i++) {
            if (this.players[i].playerNickname == playerName){
                return true;
            }
        }
        return false;
    }

}

module.exports = Sueca;
