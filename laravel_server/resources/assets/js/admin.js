
require('./bootstrap');

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import Vuetify from 'vuetify';
import VueRouter from 'vue-router';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuetify/dist/vuetify.min.css';

Vue.use(Vuetify);
Vue.use(BootstrapVue);
Vue.use(VueRouter);

const administration = Vue.component('administration', require('./components/administration.vue'));
const adminWelcome = Vue.component('adminWelcome', require('./components/adminWelcome.vue'));
const navItemsAdmin = Vue.component('navItemsAdmin', require('./components/navItemsAdmin.vue'));
const platformEmail = Vue.component('platformEmail', require('./components/platformEmail.vue'));
const statistics = Vue.component('statistics', require('./components/adminStatistics.vue'));
const deckManagement = Vue.component('decks', require('./components/deckManagement.vue'));


const routes = [
    { path: '/administration', component:administration },
    { path: '/welcome', component:adminWelcome },
    { path: '/platform', component: platformEmail },
    { path: '/statistics', component: statistics },
    { path: '/decks', component: deckManagement}
];

const router = new VueRouter({
    routes:routes
});


const app = new Vue({
    router,
    el: '#app',
    data: {
        adminAuthed: false
    },
    methods: {
        isAuthed() {
            if(window.localStorage.getItem('admin_access_token')){
                this.adminAuthed = true;
            }else{
                this.adminAuthed = false;
            }
        }
    },
    created()
    {
        this.isAuthed()
    },

});
