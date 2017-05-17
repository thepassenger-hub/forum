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
        <div class="columns">
            <div class="column is-9">
                <paginate v-if="repliesToShow.length > perPage" :current="currentPage" :perPage="perPage" :posts="repliesToShow"
                    @pageClicked="currentPage = $event; this.VueScrollTo.scrollTo('.column.is-9');" >
                </paginate>
                <reply v-for="reply in repliesToShow.slice(0+perPage*(currentPage-1), perPage*currentPage)" :reply="reply" :key="reply.id" @delete="deleteReply"></reply>
            </div>
            <div class="column is-3" id="filter">
                <input type="text" id="filter-input" class="input" placeholder="Filter by user" v-model="filterKey">
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
    import Reply from '../../models/Reply';
    import showNotificationsMixin from '../../mixins/showNotificationsMixin'

    export default {
        data() {
            return {
                replies: [],
                filterKey: '',
                currentPage: 1,
                perPage: 20,
                errorMessage: ''
            }
        },
        mixins: [showNotificationsMixin],
        created() {
            this.$root.path.update(this.$route.path);
            this.getReplies();
        },
        beforeRouteEnter: (to, from, next) => {
            next(vm => {
                vm.$root.user.isAdmin ? next() : next('/');
            });
        },
        methods: {
            getReplies() {
                axios.get('replies')
                    .then(response => {
                        response.data.forEach(reply => this.replies.push(new Reply(reply)));
                    })
                    .catch(error => console.log(error.response.data))
            },
            deleteReply(replyId) {
                axios.delete('admin/replies/'+replyId)
                    .then(response => {
                        this.replies = [];
                        this.getReplies();
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
            repliesToShow(){
                let toShow = [];
                this.replies.forEach(reply => {
                    if (reply.creator.username.toLowerCase().match(this.filterKey.toLowerCase())) toShow.push(reply);
                });
                return toShow;
            }
        },
        components: {
            'paginate': require('../../components/Paginate.vue'),
            'reply': require('../../components/admin/Reply'),
            'error': require('../../components/Error')
        }
    }
</script>