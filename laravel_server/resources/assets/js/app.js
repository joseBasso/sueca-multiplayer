
require('./bootstrap');

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import Vuetify from 'vuetify';
import VueSocketio from 'vue-socket.io';
import VueRouter from 'vue-router';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify);
Vue.use(BootstrapVue);
Vue.use(VueSocketio, 'http://192.168.10.10:8080');
Vue.use(VueRouter);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const login = Vue.component('login', require('./components/login.vue'));
const register = Vue.component('register', require('./components/register.vue'));
const navItems = Vue.component('nav-items', require('./components/navItems.vue'));
const userProfile = Vue.component('user-profile', require('./components/userProfile.vue'));
const userStatistics = Vue.component('user-statistics', require('./components/userStatistics.vue'));
const lobby = Vue.component('lobby', require('./components/lobby.vue'));
const games = Vue.component('games', require('./components/games.vue'));



const routes = [
    { path: '/login', component:login },
    { path: '/register', component:register },
    { path: '/account', component:userProfile },
    { path: '/statistics', component:userStatistics },
    { path: '/lobby', component:lobby },
    { path: '/multiplayer', component: games}


];

const router = new VueRouter({
    routes:routes
});

const app = new Vue({
    router,
    el: '#app',
    data: {
        userAuthed: false
    },
    methods: {
        isAuthed() {
            if(window.localStorage.getItem('access_token')){
                this.userAuthed = true;
            }else{
                this.userAuthed = false;
            }
        }
    },
    created()
    {
        this.isAuthed()
    },

});
