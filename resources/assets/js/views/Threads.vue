<template>
    <div class="column is-9">
        <h1 class="title">List of threads</h1>
        <button class="button" v-if="isLogged && $root.username" @click="createNewThread">Create new Thread</button>
        <thread v-for="thread in threads" :key="thread.id" @clicked="goToThread(thread)" :thread="thread">
            {{thread.title}} 
            <p slot="body">{{thread.body}}</p>
        </thread>
    </div>
</template>

<script>
    import Thread from '../models/Thread';
    import isLoggedMixin from '../mixins/IsLoggedMixin';

    export default {
        data() {
            return {
                threads: [],
                isLogged: false,
                channel: this.$route.params.channel

            }
        },
        watch: {
            '$route': function(){
                this.channel = this.$route.params.channel;
                this.threads = [];
                this.getThreads();
                this.$root.path.update(this.$route.path);
                
            }
        },
        mixins:[isLoggedMixin],
        
        created() {
            this.getThreads();
            this.checkIfLogged()
                .then(response => this.isLogged = response)
                .catch(error => {
                    this.isLogged = false;
                    console.log(error);
                });
            this.$root.path.update(this.$route.path);
        },
        methods: {
            getThreads(){
                var vm = this;
                axios.get('/channels/'+vm.channel+'/threads')
                     .then(response => {
                         response.data.forEach(thread => {
                             vm.threads.push(new Thread(thread));
                         })
                     })
                     .catch(error => console.log(error));
            },

            goToThread(thread){
                this.$router.push({ path: '/'+this.channel+'/'+thread.slug});
            },

            createNewThread(){
                this.$router.push({path: '/' + this.channel +'/new-thread'});
            },
            

            
        },
        components: {
            'thread': require('../components/Thread.vue')
        },

    }
</script>