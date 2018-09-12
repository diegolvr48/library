import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VeeValidate from 'vee-validate';
import { library } from '@fortawesome/fontawesome-svg-core'
import {
    faPlus,
    faEdit,
    faTrash
} from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import bModal from 'bootstrap-vue/es/components/modal/modal'
import bModalDirective from 'bootstrap-vue/es/directives/modal/modal'
import Pagination from 'laravel-vue-pagination';
import VueSweetalert2 from 'vue-sweetalert2';

import App from './App.vue';
import Book from './components/BookComponent.vue';
import Login from './components/LoginComponent.vue';
import Category from './components/CategoryComponent.vue';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(VeeValidate);
Vue.use(VueSweetalert2);

Vue.component('pagination', Pagination);

Vue.component('b-modal', bModal);
Vue.directive('b-modal', bModalDirective);
library.add(faPlus);
library.add(faEdit);
library.add(faTrash);

Vue.component('font-awesome-icon', FontAwesomeIcon)

axios.defaults.baseURL = 'http://localhost:8000/api';

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'book',
            component: Book,
            meta: {
                auth: true
            }
        },
        {
            path: '/category',
            name: 'category',
            component: Category,
            meta: {
                auth: true
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false
            }
        },
    ]
});

Vue.router = router;

Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

App.router = Vue.router
new Vue(App).$mount('#app');
