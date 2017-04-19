<template>
    <div class="column is-9">
        <thread v-for="thread in threads" :thread="thread" :key="thread.name"
            @clicked="goToThread(thread.channel.slug + '/'+thread.slug)" >
                {{thread.title}}
                <router-link slot="channel" :to="thread.channel.slug">{{thread.channel.name}}</router-link>
                <p slot="body"> {{thread.body}}</p>
        </thread>
    </div>
</template>

<script>
    import Thread from '../models/Thread';
    export default {

        data() {
            return {
                threads: [],
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
        }
    }
</script>
