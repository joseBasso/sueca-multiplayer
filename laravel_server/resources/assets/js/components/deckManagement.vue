<template>
    <v-app>
        <v-container>
            <h1>{{title}}</h1>
            <hr>
            <v-select
                    :items="decks"
                    label="View Deck"
                    item-text="name"
                    item-value="id"
                    v-model="deck"
                    @change="getCardsFromDeck()"
                    return-object
            ></v-select>

            <div v-if="deckSelected">
                <img height="100" v-bind:src="'storage/' + base_path + deck.hidden_face_image_path" alt="N/A">
                <hr>
                <div class="row">
                    <div v-for= "card in cards" :key="card.id">
                        <div class="col-sm-1"> <img v-bind:src="getImg(card)" alt="N/A" height=100/></div>
                    </div>
                </div>

                <v-btn dark color="blue" @click.native="editing = !editing">
                    <div v-if="!editing">Edit</div>
                    <div v-else>Cancel</div>
                </v-btn>

                <v-btn dark color="blue" @click.native="toggleActive()">
                    <div v-if="deck.active == 1">Deactivate</div>
                    <div v-else>Activate</div>
                </v-btn>

                <div v-if="editing">
                    <v-container row>
                        <form @submit.prevent="submitHiddenFace()">
                            <label for="imageFace">Hidden Face</label>
                            <input @change="handleHiddenFaceImage($event)" type="file" accept="image/png" id="imageFace" name="imageFace" />
                            <v-btn size="xs" color="pink" outline type="submit">Confirm</v-btn>
                        </form>
                        <v-flex column v-for="(card,index) in cards" :key="index">
                            <form @submit.prevent="changeCard(card, index)">
                                <label for="index">{{card.value}}
                                    <span color="red" v-if="card.suite=='Heart'">♥</span>
                                    <span v-else-if="card.suite=='Spade'">♠</span>
                                    <span v-else-if="card.suite=='Diamond'">♦</span>
                                    <span v-else-if="card.suite=='Club'">♣</span>
                                </label>
                                <input @change="handleFile($event, index)" type="file" accept="image/png" id="index" name="'card' + index" />
                                <v-btn size="xs" color="pink" outline type="submit">Confirm</v-btn>
                            </form>
                        </v-flex>
                    </v-container>
                </div>
            </div>
                <hr>
                <br>
                <br>
                <h3>Create deck</h3>
                <v-text-field
                    label="Deck Name"
                    v-model="newDeck"
                ></v-text-field>
                <v-btn dark color="pink" @click.native="createDeck()">
                    Create New Deck
                </v-btn>

            <v-snackbar v-model="showInfo" type="info" outline>
                {{infoMessage}}
                <v-btn dark flat @click="showInfo = false">
                    Close
                </v-btn>
            </v-snackbar>

        </v-container>

    </v-app>
</template>

<script type="text/javascript">
    export default {
        data: function(){
            return {
                title: 'Decks Management',
                decks: [],
                base_path: '',
                cards: [],
                deck: null,
                deckSelected: false,
                editing: false,
                cardChanged: [],
                showInfo: false,
                infoMessage: '',
                newDeck: '',
                hiddenFaceImage: null,
            }
        },
        methods: {
            toggleActive: function(){
                let self = this;
                axios.patch('api/decks/' + this.deck.id + '/toggleActive', null,{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token')
                    }
                })
                .then(function(response){
                    self.showInfo = true;
                    self.infoMessage = response.data;
                    self.deck.active = !self.deck.active;
                }).catch(error => {
                    console.log(error.response);
                });
            },
            createDeck: function(){
                let self = this;
                axios.post('api/decks/', { name: this.newDeck }, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token')
                    }
                })
                .then(function(response){
                    self.getAllDecks();
                    self.showInfo = true;
                    self.infoMessage = "New Deck Created";
                }).catch(error => {
                    console.log(error.response);
                });
            },
            getImg: function(card){
                return 'storage/' + this.base_path + card.path;
            },
            handleFile: function(event, index) {
                this.cardChanged[index] = event.target.files[0];
            },
            handleHiddenFaceImage: function(event, index) {
                this.hiddenFaceImage = event.target.files[0];
            },
            submitHiddenFace: function(){
                let formData = new FormData();
                formData.append('file', this.hiddenFaceImage);
                let axiosConfig = {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token')
                    }
                };
                let self = this;
                axios.post('api/decks/' + this.deck.id + '/hiddenFace', formData, axiosConfig)
                    .then(function(response){
                        self.getCardsFromDeck();
                        self.showInfo = true;
                        self.infoMessage = 'Hidden Face Image Changed!';
                    }).catch(error => {
                    console.log(error.response);
                });
            },
            changeCard: function(card,index){
                let formData = new FormData();
                formData.append('file', this.cardChanged[index]);
                formData.append('id', card.id);
                formData.append('deck', this.deck.id);

                let axiosConfig = {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token')
                    }
                };
                let self = this;
                axios.post('api/decks/' + this.deck.id + '/changeCard', formData, axiosConfig)
                    .then(function(response){
                        self.getCardsFromDeck();
                        self.showInfo = true;
                        self.infoMessage = 'Card changed!';
                    }).catch(error => {
                    console.log(error.response);
                });
            },
            getAllDecks: function () {
                axios.get('api/decks',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.decks = response.data;

                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
            getBasePath: function(){
                axios.get('api/decks/path',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.base_path = response.data;
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
            getCardsFromDeck: function(){
                axios.get('api/decks/' + this.deck.id, {
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        this.cards = response.data;
                        this.deckSelected = true;
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
        },
        mounted(){
            this.getBasePath();
            this.getAllDecks();
        },
    }
</script>
