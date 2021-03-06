<template>
    <div class="column is-9" v-if="thread">
        <thread :thread="thread" :key="thread.id">{{thread.title}} 
            <p slot="body" class="thread-body" v-html="markdown(thread.body)"></p>
        </thread>
        <reply v-if="thread.replies" v-for="reply in thread.replies.slice(0+perPage*(currentPage-1), perPage*currentPage)"
            :reply="reply" :key="reply.id" @delete="deleteReply" @remove="removeReply" @edit="editReply">
            <p class="reply-creator" slot="username">
                <router-link :to="'/@'+reply.creator.username">{{reply.creator.username}}</router-link>
            </p>
            <p class="reply-body" slot="body" v-html="markdown(reply.body)"></p>
        </reply>
        <paginate v-if="thread.replies.length > perPage" :current="currentPage" :perPage="perPage" :posts="thread.replies"
            @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.replies');">
        </paginate>
        <hr>
        <new-reply v-if="isLogged && $root.username" :thread="threadPath" @posted="newReply" 
            @error="showError($event); $scrollTo('#new-reply-button')"></new-reply>
        <transition name="fade">
            <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
        </transition>
    </div>
</template>

<script>

    import ThreadWithReplies from '../models/ThreadWithReplies';
    import isLoggedMixin from '../mixins/IsLoggedMixin';
    import showNotificationsMixin from '../mixins/showNotificationsMixin';

    export default {
        data(){
            return {
                thread: null,
                threadPath: this.$route.params.thread,
                channel: this.$route.params.channel,
                isLogged: false,
                currentPage: 1,
                perPage: 10,
                errorMessage: false
            }
        },

        mixins:[isLoggedMixin, showNotificationsMixin],

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
            },
            deleteReply(replyId){
                axios.delete('/replies/'+replyId)
                    .then(response => this.getThread())
                    .catch(error => {
                        this.showError(error);
                        this.$scrollTo('#new-reply-button');
                    })
            },
            removeReply(replyId){
                axios.delete('/admin/replies/'+replyId)
                    .then(response => this.getThread())
                    .catch(error => {
                        this.showError(error);
                        this.$scrollTo('#new-reply-button');
                    })
            },
            editReply(replyMessage, replyId){
                axios.patch('/replies/'+replyId, {
                    body: replyMessage
                })
                .catch(error => {
                    this.showError(error);
                    this.$scrollTo('#new-reply-button');
                });
            },
            markdown(text){
                return marked(text, {sanitize: true});
            },

            newReply(){
                var vm = this;
                axios.get('/channels/'+this.channel+'/'+this.threadPath)
                     .then(response => {
                            if (response.data) {
                                vm.thread = new ThreadWithReplies(response.data);
                                let pages = this.thread.replies.length
                                this.currentPage = Math.ceil(pages / this.perPage);
                            }
                     })
                     .catch(error => console.log(error));
            }
        },
        components: {
            'thread': require('../components/Thread.vue'),
            'reply': require('../components/Reply.vue'),
            'newReply': require('../components/NewReply.vue'),
            'paginate': require('../components/Paginate.vue'),
            'error': require('../components/Error.vue')
        }
    }
</script>