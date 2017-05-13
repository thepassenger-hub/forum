<template>
    <div class="column is-9">
        <paginate v-if="threads.length > perPage" :current="currentPage" :perPage="perPage" :posts="threads"
            @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.column.is-9');" >
        </paginate>
        <thread v-for="thread in threads.slice(0+10*(currentPage-1), 10*currentPage)" :thread="thread" :key="thread.name"
            @clicked="goToThread(thread.channel.slug + '/'+thread.slug)">
                <a >{{thread.title}}</a>
                <router-link slot="channel" :to="thread.channel.slug" class="channel-link">{{thread.channel.name}}</router-link>
                <p class="thread-body" slot="body"> {{thread.body | truncate(200)}}</p>
        </thread>
        <paginate v-if="threads.length > perPage" :current="currentPage" :perPage="perPage" :posts="threads"
            @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.column.is-9');" >
        </paginate>
    </div>
</template>

<script>
    import Thread from '../models/Thread';

    export default {

        data() {
            return {
                threads: [],
                currentPage: 1,
                perPage: 10
            }
        },
        created(){
            this.getLatestThreads();            
            this.$root.path.update(this.$route.path);
            this.$root.searchQuery = '';
        },
        watch: {
            '$route': function(){
                this.threads = [];
                this.currentPage = 1,
                this.getLatestThreads();
                this.$root.path.update(this.$route.path);
                this.$root.searchQuery = '';
            }
        },
        methods: {
            goToThread(threadPath){
                this.$router.push({ path: '/' + threadPath});
            },

            getLatestThreads(){
                var vm = this;
                axios.get('/threads', {
                        params: vm.$route.query
                    })
                    .then(response => {
                        response.data.forEach(thread => this.threads.push(new Thread(thread)));
                    })
                    .catch(error => console.log(error.response.data));
            }
        },

        components: {
            'thread': require('../components/Thread.vue'),
            'paginate': require('../components/Paginate.vue')
        }
    }
</script>
