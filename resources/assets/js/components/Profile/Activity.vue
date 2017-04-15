<template>
    <div class="container" v-if="replies">
        <div v-for="reply in replies">
            <p v-if="reply.thread" class="color-text-lightest mb-1"><a href="#">{{profile.user.username}}</a> has left a reply on <a href="#">{{reply.thread.title}}</a> | {{reply.created_at}}</p>
            <div class="content">
                <p>{{reply.body}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['profile'],
        data(){
            return {
                replies: null
            }
        },
        created(){
            this.getReplies();
        },

        methods: {
            getReplies(){
                axios.get('/profile/replies')
                     .then(response => this.replies = response.data)
                     .catch(error => console.log(error.response.data))
            }
        }

    }
</script>