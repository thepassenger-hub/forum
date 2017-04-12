import './bootstrap';
import router from './routes';
// Vue.component('createFile', require('./components/CreateFile.vue'));
// Vue.component('channel', require('./components/Channel.vue'));
import isLoggedMixin from './mixins/IsLoggedMixin';

const app = new Vue({
    el: '#app',
    router,
    mixins:[isLoggedMixin],
    data: {
        username: false
    },
    created(){
        this.checkIfLogged()
            .then(response => {
                    this.username = response ? response.username : false}
                )                    
            .catch(error => console.log(error));
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
