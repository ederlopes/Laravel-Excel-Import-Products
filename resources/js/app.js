import Vue from 'vue'

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue);

import axios from "axios";
window.axios = axios;

window.axios.defaults.baseURL = 'http://localhost:8000/api';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

import Products from './views/Products'

new Vue({
    el: '#app',
    components: {
        Products
    }
});
