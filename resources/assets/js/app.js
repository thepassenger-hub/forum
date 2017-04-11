import './bootstrap';
import router from './routes';
// Vue.component('createFile', require('./components/CreateFile.vue'));
// Vue.component('channel', require('./components/Channel.vue'));
import isLoggedMixin from './mixins/IsLoggedMixin';

const app = new Vue({
    el: '#app',
    router,
    mixins:[isLoggedMixin],

});
