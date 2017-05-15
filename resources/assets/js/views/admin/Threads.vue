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
        
    </div>
</template>

<script>
    import User from '../../models/User';
    export default {
        data() {
            return {
                users: []
            }
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
            }
        }
    }
</script>