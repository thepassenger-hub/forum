<template>
    <div class="thread">
        <article class="media">
            <figure class="media-left is-hidden-mobile">
                <p class="image is-64x64">
                    <img :src="thread.creator.profile.avatar">
                </p>
            </figure>
            <div class="media-content" :class="{'thread-if-reply-counter': thread.replies_count !== undefined}">
                <div class="content">
                    <p class="title is-4 thread-title" @click="$emit('clicked')"><slot></slot></p>
                    <slot name="channel"></slot>
                    <span class="created-at">{{thread.updated_at | fromNow}}</span>
                    by
                    <strong class="created-by">
                        <router-link :to="'/@'+thread.creator.username">{{thread.creator.username}}</router-link>
                    </strong>
                    <slot name="body"></slot>
                </div>
            </div>
            
            <div class="reply-count replies-count is-hidden-mobile">
                {{thread.replies_count}}
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        props: ['thread']
    }
</script>