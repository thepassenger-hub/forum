<template>
    <article class="media replies">
        <figure class="media-left">
            <p class="image is-64x64">
                <img :src="reply.creator.profile.avatar">
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <slot name="username"></slot>
                <span class="created-at">{{reply.createdAt | fromNow}}</span>
                <slot name="body"></slot>
                <small><a>Reply</a></small>
            </p>
            </div>
        </div>
    </article>





</template>

<script>
    export default {
        props: ['reply']
    }
</script>