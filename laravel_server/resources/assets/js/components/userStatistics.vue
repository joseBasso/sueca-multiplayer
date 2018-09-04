<template>
    <v-app>
    <v-container>

        <div v-if="playerNickname">
            <h1>{{ playerNickname }}'s statistics</h1>
            <hr/>
            <p><strong>Total Games:</strong> {{ playerTotalGames }}</p>
            <p><strong>Total Points:</strong> {{ playerTotalPoints }}</p>
            <p><strong>Average Points:</strong> {{ playerAveragePoints }}</p>
        </div>
        <hr/>
        <h5>Total Players: {{totalPlayers}}</h5>
        <h5>Total Games: {{totalGames}}</h5>

        <v-layout row>
            <v-flex xs4>
                <v-data-table
                :headers="gamesPlayedHeaders"
                :items="top5MostGames"
                hide-actions
                class="elevation-1"
                disable-initial-sort
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.nickname }}</td>
                <td>{{ props.item.total_games_played }}</td>
            </template>
        </v-data-table>
            </v-flex>
            <v-flex xs4>
                <v-data-table
                        :headers="mostPointsHeaders"
                        :items="top5MostPoints"
                        hide-actions
                        class="elevation-1"
                        disable-initial-sort
                >
                    <template slot="items" slot-scope="props">
                        <td>{{ props.item.nickname }}</td>
                        <td>{{ props.item.total_points }}</td>
                    </template>
                </v-data-table>
            </v-flex>
            <v-flex xs4>
                <v-data-table
                        :headers="averagePointsHeaders"
                        :items="top5BestAverage"
                        hide-actions
                        class="elevation-1"
                        disable-initial-sort
                >
                    <template slot="items" slot-scope="props">
                        <td>{{ props.item.nickname }}</td>
                        <td>{{ props.item.average }}</td>
                    </template>
                </v-data-table>
            </v-flex>
        </v-layout>




    </v-container>
    </v-app>
</template>

<script>

    export default {
        data: function () {
            return {
                gamesPlayedHeaders: [
                    { text: 'User', sortable: false },
                    { text: 'Games Played', value: 'total_games_played' },
                ],
                mostPointsHeaders: [
                    { text: 'User', sortable: false },
                    { text: 'Total Points', value: 'total_points' },
                ],
                averagePointsHeaders: [
                    { text: 'User', sortable: false },
                    { text: 'Average Points', value: 'average' },
                ],
                totalGames: 0,
                totalPlayers: 0,
                playerNickname: undefined,
                playerTotalDraws: 0,
                playerTotalDefeats: 0,
                playerTotalPoints: 0,
                playerAveragePoints: 0,
                playerTotalGames: 0,
                playerTotalWins: 0,
                top5MostGames: null,
                top5MostPoints: null,
                top5BestAverage: null,
                nickname: null,
            }
        },
        methods: {
            getAuthenticatedUserStatistics: function(){
                axios.get('api/platform/statistics/authed'
                    ,{
                        headers: {
                            Accept: 'application/json',
                            Authorization: window.localStorage.getItem('access_token'),
                        }
                    }
                )
                    .then(response=>{
                        this.playerNickname = response.data.authenticatedUserStatistics.user_nickname;
                        this.playerTotalGames = response.data.authenticatedUserStatistics.user_total_games_played;
                        this.playerTotalPoints = response.data.authenticatedUserStatistics.user_total_points;
                        this.playerAveragePoints = response.data.authenticatedUserStatistics.user_average_points;

                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },
            getUserStats: function(){
                axios.get('api/platform/statistics/users')
                    .then(response=>{
                        this.totalPlayers = response.data.total_players;
                        this.totalGames = response.data.total_games;
                        this.top5MostGames = response.data.top5MostGames;
                        this.top5MostPoints = response.data.top5MostPoints;
                        this.top5BestAverage = response.data.top5BestAverage;

                    })
                    .catch(error=>{
                        console.log(error.response);
                    })
            },

        },
        mounted() {
            this.getAuthenticatedUserStatistics();
            this.getUserStats();

        }
    }
</script>

<style scoped>

</style>