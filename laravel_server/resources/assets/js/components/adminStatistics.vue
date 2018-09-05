<template>
    <v-app>
        <v-container>
            <h1>{{title}}</h1>
            <br/>
            <h5>Total Players: {{totalPlayers}}</h5>
            <h5>Total Games: {{totalGames}}</h5>
            <hr/>
            <v-data-table
                    :headers="headers"
                    :items="users"
                    class="elevation-5"
                    :pagination.sync="pagination"
            >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.total_games_played }}</td>
                    <td>{{ props.item.wins }}</td>
                    <td>{{ props.item.draws }}</td>
                    <td>{{ props.item.losses }}</td>
                </template>
            </v-data-table>

        </v-container>
    </v-app>
</template>

<script>

    export default {
        data: function () {
            return {
                title: 'Statistics',
                pagination: {
                    sortBy: 'total_games_played',
                    descending: 'true'
                },
                headers: [
                    { text: 'User', sortable: false },
                    { text: 'Total Games', value: 'total_games_played' },
                    { text: 'Wins', value: 'wins'},
                    { text: 'Draws', value: 'draws'},
                    { text: 'Defeats', value: 'losses'}
                ],
                users: [],
                totalGames: 0,
                totalPlayers: 0,
                playerTotalDraws: 0,
                playerTotalDefeats: 0,
                playerTotalPoints: 0,
                playerTotalWins: 0,
            }
        },
        methods: {
            getUserStats: function(){
                axios.get('api/platform/statistics/users')
                    .then(response=>{
                        this.totalPlayers = response.data.total_players;
                        this.totalGames = response.data.total_games;
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },

            getAllUsers: function(){
                axios.get('api/platform/statistics/userList',{
                    headers: {
                        Accept: 'application/json',
                        Authorization: window.localStorage.getItem('admin_access_token'),
                    }
                })
                    .then(response=>{
                        console.log(response.data);
                        this.users = response.data;
                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },

        },
        components: {
        },
        mounted() {
            this.getAllUsers();
            this.getUserStats();

        }
    }
</script>

