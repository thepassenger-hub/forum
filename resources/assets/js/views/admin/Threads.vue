<template>
    <div class="column is-9">
        <div class="tabs is-centered is-boxed">
            <ul>
                <router-link tag="li" :to="{ name: 'admin-users' }">
                    <a>Users</a>
                </router-link>
                <router-link  tag="li" :to="{ name: 'admin-threads' }"><a>Threads</a></router-link>
                <router-link  tag="li" :to="{ name: 'admin-replies' }"><a>Replies</a></router-link>
            </ul>
        </div>
        <div class="columns admin-main">
            <div class="column is-9">
                <paginate v-if="threadsToShow.length > perPage" :current="currentPage" :perPage="perPage" :posts="threadsToShow"
                    @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.column.is-9');" >
                </paginate>
                <thread v-for="thread in threadsToShow.slice(0+perPage*(currentPage-1), perPage*currentPage)" :thread="thread" :key="thread.title" @delete="deleteThread"></thread>
            </div>
            <div class="column is-3" id="filter">
                <input type="text" id="filter-input" class="input" placeholder="Filter by title" v-model="filterKey">
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
    import Thread from '../../models/Thread';
    import showNotificationsMixin from '../../mixins/showNotificationsMixin'

    export default {
        data() {
            return {
                threads: [],
                filterKey: '',
                currentPage: 1,
                perPage: 20,
                errorMessage: ''
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
            this.getThreads();
        },
        methods: {
            getThreads() {
                axios.get('threads')
                    .then(response => {
                        response.data.forEach(thread => this.threads.push(new Thread(thread)));
                    })
                    .catch(error => console.log(error.response.data))
            },
            deleteThread(threadSlug) {
                axios.delete('admin/threads/'+threadSlug)
                    .then(response => {
                        this.threads = [];
                        this.getThreads();
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error.response.data).forEach(field => out += error.response.data[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('#messages', {'offset': -30});
                    })
            }
        },
        computed: {
            threadsToShow(){
                let toShow = [];
                this.threads.forEach(thread => {
                    if (thread.title.toLowerCase().match(this.filterKey.toLowerCase())) toShow.push(thread);
                });
                return toShow;
            }
        },
        components: {
            'paginate': require('../../components/Paginate.vue'),
            'thread': require('../../components/admin/Thread'),
            'error': require('../../components/Error')
        }
    }
</script>