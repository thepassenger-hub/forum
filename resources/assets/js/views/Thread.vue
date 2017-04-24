<template>
    <div class="column is-9" v-if="thread">
        <thread :thread="thread" :key="thread.id">{{thread.title}} <p slot="body">{{thread.body}}</p></thread>
        <reply v-if="thread.replies" v-for="reply in thread.replies.slice(0+10*(currentPage-1), 10*currentPage)" :key="reply.id">
            <p slot="username"><strong>{{reply.creator}}</strong></p>
            <p slot="body">{{reply.body}}</p>
        </reply>
        <paginate v-if="thread.replies.length > perPage" :current="currentPage" :perPage="perPage" :posts="thread.replies"
            @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.replies');">
        </paginate>
        <!--<button  class="button is-primary" @click="showNewReply = true">Add reply</button>-->
        <hr>
        <new-reply v-if="isLogged && $root.username" :thread="threadPath" @posted="getThread"></new-reply>
    </div>
</template>

<script>
    import ThreadWithReplies from '../models/ThreadWithReplies';
    import isLoggedMixin from '../mixins/IsLoggedMixin';
    var VueScrollTo = require('vue-scrollto');

    export default {
        data(){
            return {
                thread: null,
                threadPath: this.$route.params.thread,
                channel: this.$route.params.channel,
                isLogged: false,
                currentPage: 1,
                perPage: 10
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
                            if (response.data) vm.thread = new ThreadWithReplies(response.data);
                     })
                     .catch(error => console.log(error));
            }
        },
        components: {
            'thread': require('../components/Thread.vue'),
            'reply': require('../components/Reply.vue'),
            'newReply': require('../components/NewReply.vue'),
            'paginate': require('../components/Paginate.vue')

        }
    }
</script>