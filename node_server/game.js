/*jshint esversion: 6 */
var axios = require('axios');
let url = 'http://sueca.proj/api/games';


class Player {
    constructor(playerNickname, id) {
        this.id = id;
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
        this.visible = false;

    }
}


class Sueca {
    constructor(ID, player1Name, playerId, created_at) {
        this.gameID = ID;
        this.owner = player1Name;
        this.gameEnded = false;
        this.gameStarted = false;
        this.created_at  = created_at;
        this.players = [];
        this.cards = [];
        this.boardCards = [];
        this.players[0] = new Player(player1Name, playerId);
        this.players[1] = undefined;
        this.players[2] = undefined;
        this.players[3] = undefined;
        this.joinedPlayers = 1;
        this.team_winner = 0;
        this.turn = 0;
        this.team1_cardpoints = 0;
        this.team2_cardpoints = 0;
        this.team1_points = 0;
        this.team2_points = 0;
        // system only renounces
        this.team1_renounce = 0;
        this.team2_renounce = 0;
        //
        this.team_renounce = 0;
        this.team_checkRenounce = 0;
        this.cardTrump = undefined;

    }

    join(playerNickname, playerId){
        this.players[this.joinedPlayers] = new Player(playerNickname, playerId);
        this.players[this.joinedPlayers].id = playerId;
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
                    this.players[i].id = undefined;
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
        let self = this;
        if (!this.gameEnded && this.joinedPlayers == 4) {
            axios.put(url + '/' + this.gameID + '/start', null, {
                headers: {
                    Accept: 'application/json',
                    key: 'secret',
                }
            })
                .then(response => {
                    self.gameStarted = true;
                    self.generateDeck();
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
        this.turn = 4;
        for (var i = 0; i < 4; i++) {
            for (var j = 0; j < 10; j++) {
                if (i === 0 && j === 0){
                    this.cardTrump = this.cards[cardsDealt + j];
                    this.cards[cardsDealt + j].visible = true;
                }
                this.players[i].cards.push(this.cards[cardsDealt + j]);
            }
            cardsDealt = cardsDealt + 10;
        }
    }

    isInGame(player) {
        console.log("is in Game ");
        console.log(player);
        for (let i = 0; i < 4; i++) {
            if (this.players[i].id === player){
                return true;
            }
        }
        return false;
    }

     playCard(playerId, card){
        if (! this.isTurn(playerId)) {
            return false;
        }
            let player = this.getPlayerPosition(playerId);
            let currentPlayer = this.players[player];

            if (this.boardCards.length > 0){
                let suite = this.boardCards[0].suite;
                if (card.suite !== suite) {
                    let self = this;
                    currentPlayer.cards.forEach(function (playerCard) {
                        if (playerCard.suite === suite){
                            if (player === 0 || player === 2) {
                                self.team1_renounce = 1;
                            } else {
                                self.team2_renounce = 1;
                            }
                        }
                    })
                }

                currentPlayer.cards.splice(currentPlayer.cards.findIndex(c => c.suite == card.suite && c.value == card.value), 1);
                this.boardCards.push(card);


                if(this.boardCards.length === 4) {
                    let winningCard = null;
                    let winningCardNumber = null;
                    let self = this;
                    this.boardCards.forEach((boardCard, index) => {
                        if(winningCard == null) {
                            winningCard = boardCard;
                            winningCardNumber = index;
                        } else {
                            if (! this.beatsCard(winningCard, boardCard, suite)){
                                winningCard = boardCard;
                                winningCardNumber = index;
                            }
                        }
                    });
                    console.log("ganhou a carta: ");
                    console.log(this.boardCards[winningCardNumber]);

                    let winnerPlayer = this.turn + winningCardNumber;
                    if(winnerPlayer > 3) {
                        winnerPlayer = winnerPlayer - 4;
                    }
                    console.log("ganhou o jogador: ");
                    console.log(this.players[winnerPlayer]);

                    if(winnerPlayer === 0 || winnerPlayer === 2) {
                        self.boardCards.forEach((wonCard) => {
                            self.team1_cardpoints = self.team1_cardpoints + wonCard.points;
                        });
                    } else {
                        self.boardCards.forEach((wonCard) => {
                            self.team2_cardpoints = self.team2_cardpoints + wonCard.points;
                        });
                    }

                    this.turn = winnerPlayer + 1;
                    this.boardCards = [];

                }else {

                    if (this.turn === 4) {
                        this.turn = 1;
                    } else {
                        this.turn++;
                    }
                }

            } else {
                currentPlayer.cards.splice(currentPlayer.cards.findIndex(c => c.suite == card.suite && c.value == card.value), 1);
                this.boardCards.push(card);

                if(this.turn === 4) {
                    this.turn = 1;
                } else {
                    this.turn++;
                }
            }
            return true;

    }

    beatsCard(cardA, cardB, suite){

        if(cardA.suite === this.cardTrump.suite && cardB.suite !== this.cardTrump.suite) {
            return true;
        }

        if(cardB.suite === this.cardTrump.suite && cardA.suite !== this.cardTrump.suite) {
            return false;
        }

        if(cardA.suite === suite && cardB.suite !== suite) {
            return true;
        }

        if(cardA.suite !== suite && cardB.suite === suite) {
            return false;
        }

        if (cardA.suite === cardB.suite) {
            return cardA.ranking > cardB.ranking;
        }

        return true;
    }

    isTurn(id){
        let index = this.getPlayerPosition(id);
        return this.turn === index + 1;
    }

    getPlayerPosition(id){
        return this.players.findIndex(p => p.id === id);
    }

    checkIsGameOver() {
        if(this.team1_cardpoints + this.team2_cardpoints === 120) {
            this.gameEnded = true;
            if(this.team1_cardpoints > this.team2_cardpoints) {
                this.team_winner = 1;

                if(this.team1_cardpoints >= 61 && this.team1_cardpoints <= 90) {
                    this.team1_points = 1;
                }
                if(this.team1_cardpoints >= 91 && this.team1_points <= 119) {
                    this.team1_points = 2;
                }
                if(this.team1_cardpoints === 120) {
                    this.team1_points = 4;
                }

            } else {
                this.team_winner = 2;

                if(this.team2_cardpoints >= 61 && this.team2_cardpoints <= 90) {
                    this.team2_points = 1;
                }
                if(this.team2_cardpoints >= 91 && this.team2_points <= 119) {
                    this.team2_points = 2;
                }
                if(this.team2_cardpoints === 120) {
                    this.team2_points = 4;
                }
            }
        }
    }
}

module.exports = Sueca;
