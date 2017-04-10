<template>
    <section class="section">
        <h1 class="title">List of threads</h1>
        <button class="button" v-if="isLogged" @click="createNewThread">Create new Thread</button>
        <thread v-for="thread in threads" :key="thread.id" @clicked="goToThread(thread)">{{thread.title}}</thread>
    </section>
</template>

<script>
    import Thread from '../models/Thread';
    export default {
        data() {
            return {
                threads: [],
                isLogged: false,
            }
        },
        created() {
            this.getThreads();
            this.checkIfLogged();
        },
        methods: {
            getThreads(){
                var vm = this;
                let channel = this.$router.currentRoute.params.channel;
                axios.get('/channels/'+channel+'/threads')
                     .then(response => {
                         response.data.forEach(thread => {
                             vm.threads.push(new Thread(thread));
                         })
                     })
                     .catch(error => console.log(error));
            },

            goToThread(thread){
                let channel = this.$router.currentRoute.params.channel;
                this.$router.push({ path: '/'+channel+'/'+thread.id});
            },

            createNewThread(){
                let channel = this.$router.currentRoute.params.channel;
                this.$router.push({path: '/' + channel +'/new-thread'});
            },

            checkIfLogged(){
                var vm = this;
                axios.get('/sessionStatus')
                     .then(response => {
                         this.isLogged = response.data.isLogged;
                     })
                     .catch(error => {
                         this.isLogged = false;
                         console.log(error);
                     })
            }
        },
        components: {
            'thread': require('../components/Thread.vue')
        },

    }
</script>