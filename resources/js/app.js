/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Form from './utilities/Form';

window.Vue = require('vue');
window.Form = Form;
window.events = new Vue();

Vue.prototype.auth = window.auth;

import Toasted from 'vue-toasted';
Vue.use(Toasted,  { 
    theme: "toasted-primary", 
	position: "bottom-left", 
    duration: 5000,
    action: {
        text: 'Close',
        onClick: (e, toastObject) => {
            toastObject.goAway(0);
        }
    },
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('subscription-checkout', require('./components/SubscriptionCheckout.vue').default);
Vue.component('sign-up', require('./components/SignUp.vue').default);
Vue.component('member-slot-checkout', require('./components/MemberSlotCheckout.vue').default);
Vue.component('renew-subscription', require('./components/RenewSubscription.vue').default);
Vue.component('new-task', require('./components/tasks/NewTask.vue').default);
Vue.component('tasks-table', require('./components/tasks/TasksTable.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});