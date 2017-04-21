<template>
    <div class="column is-9">
        <thread v-for="thread in threads.slice(0+10*(currentPage-1), 10*currentPage)" :thread="thread" :key="thread.name"
            @clicked="goToThread(thread.channel.slug + '/'+thread.slug)" >
                <a>{{thread.title}}</a>
                <router-link slot="channel" :to="thread.channel.slug">{{thread.channel.name}}</router-link>
                <p slot="body"> {{thread.body | truncate(200)}}</p>
        </thread>
        <paginate-links :current="currentPage" :perPage="perPage" :threads="threads"
            @pageClicked="currentPage = $event" >
        </paginate-links>
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
            
        },
        methods: {
            goToThread(threadPath){
                this.$router.push({ path: '/'+threadPath});
            },

            getLatestThreads(){
                axios.get('/threads')
                     .then(response => {
                         response.data.forEach(thread => this.threads.push(new Thread(thread)));
                     })
                     .catch(error => console.log(error.response.data));
            }
        },

        components: {
            'thread': require('../components/Thread.vue'),
            'paginateLinks': require('../components/Paginate.vue')
        }
    }
</script>
