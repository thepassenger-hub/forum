<template>
    <section class="section">
        <thread v-if="thread" :key="thread.id">{{thread.title}} <p slot="body">{{thread.body}}</p></thread>
    </section>
</template>

<script>
    import Thread from '../models/Thread';
    export default {
        data(){
            return {
                thread: null
            }
        },
        created(){
            this.getThread();
        },
        methods: {
            getThread(){
                var vm = this;
                let thread = this.$router.currentRoute.params.thread;
                let channel = this.$router.currentRoute.params.channel;
                axios.get('/channels/'+channel+'/'+thread)
                     .then(response => vm.thread = new Thread(response.data))
                     .catch(error => console.log(error));
            }
        },
        components: {
            'thread': require('../components/Thread.vue')
        }
    }
</script>