<template>
    <div class="view-profile ">
        <div class="columns">
            <div class="column is-9">
                <article class="media">
                    <figure class="image is-128x128">
                        <img id="avatar" :src="profile.avatar">
                    </figure>
                    <div class="media-content">
                        <div v-if="profile" class="content" id="profile-username">
                            <p class="title is-4 username">
                                {{profile.user.username}}
                            </p>
                            <p class="subtitle profile-name" v-if="profile.name">
                                aka {{profile.name}}
                            </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        <nav class="level" id="profile-infos" v-if="profile.user">
            <div class="level-item">
                    <h4> Member since <strong>{{profile.user.created_at | fromNow}}</strong></h4>
                </div>
                <div class="level-item">
                    <h4> From <strong>{{profile.location}}</strong></h4>
                </div>
                <div class="level-item">
                    <h4> <strong>{{profile.user.threads_count}}</strong> threads created</h4>
                </div>
                <div class="level-item">
                    <h4> <strong>{{profile.user.replies_count}}</strong> replies posted</h4>
                </div>
        </nav>
        <hr>
        <h5 class="title is-5" id="profile-bio">{{profile.bio}}</h5>
        <hr>
        <div v-if="profile.user">
            <div class="columns" v-for="(replies, day) in repliesByMonth">
                <div class="column is-2">
                    {{day}}
                </div>
                <div class="column is-10">
                    <div v-for="reply in replies">
                        <p v-if="reply.thread" class="color-text-lightest mb-1">
                            Reply on
                            <strong><router-link :to="'/' + reply.thread.channel.slug + '/' + reply.thread.slug">
                                {{reply.thread.title}}
                            </router-link></strong> | <strong>{{reply.created_at | fromNow}}</strong>
                        </p>
                        <br>
                        <div class="content">
                            <p v-html="markdown(reply.body)"></p>
                        </div>
                        <hr>    
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
    // var marked = require('marked');

    export default {
        props: ['profile'],
        computed: {
            registered(){
                return this.profile.created_at;
            },
            repliesByMonth() {
                let replies = {};
                this.profile.user.replies.forEach(reply => {
                    let time = moment(reply.created_at).format('D MMM YYYY');
                    replies.hasOwnProperty(time) ? replies[time].push(reply) : replies[time] = [reply];
                });
                return replies;
            }
        },
        methods: {
            markdown(text){
                return marked(text, {sanitize: true});
            }
        }
            
                
    }
</script>