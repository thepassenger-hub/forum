<template>
    <div class="column is-9" v-if="thread">
        <thread :key="thread.id">{{thread.title}} <p slot="body">{{thread.body}}</p></thread>
        <reply v-if="thread.replies" v-for="reply in thread.replies" :key="reply.id">
                <p slot="username"><strong>{{reply.creator}}</strong></p>
                <p slot="body">{{reply.body}}</p>
        </reply>
        <!--<button  class="button is-primary" @click="showNewReply = true">Add reply</button>-->
        <hr>
        <new-reply v-if="isLogged && $root.username" :thread="threadPath" @posted="getThread"></new-reply>
    </div>
</template>

<script>
    import ThreadWithReplies from '../models/ThreadWithReplies';
    import isLoggedMixin from '../mixins/IsLoggedMixin';

    export default {
        data(){
            return {
                thread: null,
                threadPath: this.$route.params.thread,
                channel: this.$route.params.channel,
                isLogged: false
            }
        },

        mixins:[isLoggedMixin],

        created(){
            this.getThread();
            this.checkIfLogged()
                .then(response => this.isLogged = response ? response.username : false)
                .catch(error => {
                    this.isLogged = false;
                    console.log(error);
                });
            this.$root.path.update(this.$route.path);
        },
        methods: {
            getThread(){
                var vm = this;
                
                axios.get('/channels/'+this.channel+'/'+this.threadPath)
                     .then(response => {
                            console.log(response.data);
                            if (response.data) vm.thread = new ThreadWithReplies(response.data);
                     })
                     .catch(error => console.log(error));
            }
        },
        components: {
            'thread': require('../components/Thread.vue'),
            'reply': require('../components/Reply.vue'),
            'newReply': require('../components/NewReply.vue')
        }
    }
</script>