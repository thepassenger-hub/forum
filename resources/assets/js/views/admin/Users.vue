<template>
    <div class="column is-9">
        <div class="tabs is-centered is-boxed">
            <ul>
                <router-link tag="li" class="is-active" :to="{ name: 'admin-users' }">
                    <a class="is-active">Users</a>
                </router-link>
                <router-link  tag="li" :to="{ name: 'admin-threads' }"><a>Threads</a></router-link>
                <router-link  tag="li" :to="{ name: 'admin-replies' }"><a>Replies</a></router-link>
            </ul>
        </div>
        <div class="column is-9">
            <user v-for="user in users" :user="user" :key="user.username" @suspend="askConfirmation(user.username, $event)"></user>
        </div>
        <confirmation-modal v-if="confirmation" @confirm="suspend(username, days)" @close="resetData">
            {{confirmationMessage}}
        </confirmation-modal>
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
                confirmationMessage: '',
                username: null,
                days: null,
                confirmation: false,
                errorMessage: false
            }
        },
        mixins: [showNotificationsMixin],
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
            askConfirmation(username, days) {
                this.confirmationMessage = 'Are you sure you want to suspend ' + username + ' for ' + days + ' days?';
                this.username = username;
                this.days = days;
                this.confirmation = true;
            },
            suspend(user, days) {
                axios.patch('/admin/users/'+user, {
                    days: days
                })
                    .then(response => {
                        this.resetData();
                        this.user = [];
                        this.getUser()
                    })
                    .catch(error => {
                        this.errorMessage = error.response.data;
                        this.resetData();
                        this.$scrollTo('#messages', {'offset': -30});
                    });
            },
            resetData(){
                this.confirmation = false;
                this.day = null,
                this.username = null,
                this.confirmationMessage = ''
            }
        },
        components: {
            'user': require('../../components/User'),
            'confirmationModal': require('../../components/ConfirmationModal'),
            'error': require('../../components/Error.vue'),
        }
    }
</script>