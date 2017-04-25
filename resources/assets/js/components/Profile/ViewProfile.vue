<template>
    <div class="view-profile ">
        <div class="columns">
            <div class="column is-9">
                <article class="media">
                    <figure class="image is-128x128">
                        <img :src="profile.avatar">
                    </figure>
                    <div class="media-content">
                        <div v-if="profile" class="content" id="profile-username">
                            <p class="title is-4">
                                {{profile.user.username}}
                            </p>
                            <p class="subtitle" v-if="profile.name">
                                aka {{profile.name}}
                            </p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        <nav class="level" v-if="profile.user">
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
        <h5 class="title is-5">{{profile.bio}}</h5>
        <hr>
        <div class="container" v-if="profile.user">
            <div v-for="reply in profile.user.replies">
                <p v-if="reply.thread" class="color-text-lightest mb-1">
                    Reply on
                    <router-link :to="'/' + reply.thread.channel.slug + '/' + reply.thread.slug">
                        {{reply.thread.title}}
                    </router-link> | {{reply.created_at | fromNow}}
                </p>
                <div class="content">
                    <p v-for="line in reply.body.split('\n')">{{line}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['profile'],
        computed: {
            registered(){
                return this.profile.created_at;
            }
        },
        filters: {
            fromNow(date){
                return moment(date).fromNow();
            }
        }
    }
</script>