import './bootstrap';
import router from './routes';
// Vue.component('createFile', require('./components/CreateFile.vue'));
// Vue.component('channel', require('./components/Channel.vue'));
import isLoggedMixin from './mixins/IsLoggedMixin';
import getChannelsMixin from './mixins/GetChannelsMixin';

import Path from './models/Path';

Vue.filter('truncate', function (text, value) {
    if (text.length <= value) return text;
    return text.substring(0, value) + '...';
})

Vue.filter('fromNow', function(date){
    return moment(date).fromNow();
});

const app = new Vue({
    el: '#app',
    router,
    mixins:[isLoggedMixin, getChannelsMixin],
    data: {
        user: false,
        // username: false,
        path: new Path(),
        channels: []
    },
    created(){
        this.checkIfLogged()
            .then(response => {
                    this.username = response ? response.username : false;
                    this.user = response ? response : false;
                })                    
            .catch(error => console.log(error));

        this.getChannels();
    },

    methods: {
        logout(){
            axios.post('/logout')
                 .then(response => {
                     this.setNewCsrfToken(response.data);
                     this.user = false;
                 })
                 .catch(error => console.log(error));
        },
        setNewCsrfToken(newToken){
            document.querySelector('meta[name=csrf-token]').content = newToken;
            window.Laravel = { csrfToken: document.querySelector('meta[name=csrf-token]').content };
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
        }
    },
    computed: {
        username() {
            return this.user ? this.user.username : false;
        }
    }

});
