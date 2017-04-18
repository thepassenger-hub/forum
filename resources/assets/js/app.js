import './bootstrap';
import router from './routes';
// Vue.component('createFile', require('./components/CreateFile.vue'));
// Vue.component('channel', require('./components/Channel.vue'));
import isLoggedMixin from './mixins/IsLoggedMixin';
import getChannelsMixin from './mixins/GetChannelsMixin';

import Path from './models/Path';

const app = new Vue({
    el: '#app',
    router,
    mixins:[isLoggedMixin, getChannelsMixin],
    data: {
        username: false,
        path: new Path(),
        channels: []
    },
    created(){
        this.checkIfLogged()
            .then(response => {
                    this.username = response ? response.username : false
                })                    
            .catch(error => console.log(error));

        this.getChannels();
    },

    methods: {
        logout(){
            axios.post('/logout')
                 .then(response => {
                     this.setNewCsrfToken(response.data);
                     this.username = false;
                 })
                 .catch(error => console.log(error));
        },
        setNewCsrfToken(newToken){
            document.querySelector('meta[name=csrf-token]').content = newToken;
            window.Laravel = { csrfToken: document.querySelector('meta[name=csrf-token]').content };
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
        }
    }

});
