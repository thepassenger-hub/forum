<template>
    <div class="column is-9">
        <div class="tabs is-centered is-boxed">
            <ul>
                <router-link tag="li" class="is-active" :to="{ name: 'admin-users' }">
                    <a>Users</a>
                </router-link>
                <router-link  tag="li" :to="{ name: 'admin-threads' }"><a>Threads</a></router-link>
                <router-link  tag="li" :to="{ name: 'admin-replies' }"><a>Replies</a></router-link>
            </ul>
        </div>
        <div class="columns admin-main" >
            <div class="column is-9">
                <user v-for="user in usersToShow" :user="user" :key="user.username" 
                    @suspend="suspend" @enable="enable"></user>
            </div>
            <div class="column is-3" id="filter">
                <input type="text" id="filter-input" class="input" placeholder="Filter by username" v-model="filterKey">
            </div>
        </div>
        <div id="messages">
            <transition name="fade">
                <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
            </transition>
        </div>
    </div>
</template>

<script>
    import User from '../../models/User';
    import showNotificationsMixin from '../../mixins/showNotificationsMixin'
    export default {
        data() {
            return {
                users: [],
                errorMessage: false,
                filterKey: ''
            }
        },
        mixins: [showNotificationsMixin],
        beforeRouteEnter: (to, from, next) => {
            next(vm => {
                vm.$root.user.isAdmin ? next() : next('/');
            });
        },
        created() {
            this.$root.path.update(this.$route.path);
            this.getUsers();
        },
        methods: {
            getUsers() {
                axios.get('users')
                    .then(response => {
                        response.data.forEach(user => this.users.push(new User(user)));
                    })
                    .catch(error => console.log(error.response.data))
            },
            suspend(user, days) {
                axios.patch('/admin/users/'+user+'/ban', {
                    days: days
                })
                    .then(response => {
                        this.users = [];
                        this.getUsers();
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error.response.data).forEach(field => out += error.response.data[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('#messages', {'offset': -30});
                        
                    });
            },
            enable(user) {
                axios.patch('/admin/users/'+user+'/enable')
                    .then(response => {
                        this.users = [];
                        this.getUsers();
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error.response.data).forEach(field => out += error.response.data[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('#messages', {'offset': -30});
                    });
            }
        },
        computed: {
            usersToShow() {
                let toShow = [];
                this.users.forEach(user => {
                    if (user.username.toLowerCase().match(this.filterKey.toLowerCase())) toShow.push(user);

                });
                return toShow;
            }
        },
        components: {
            'user': require('../../components/admin/User'),
            'error': require('../../components/Error.vue')
        }
    }
</script>