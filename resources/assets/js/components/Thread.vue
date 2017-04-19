<template>
    <div @click="$emit('clicked')" class="box">
        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">
                <img src="http://bulma.io/images/placeholders/128x128.png">
                </p>
            </figure>
            <div class="media-content">
                <div class="content">
                    <h1><slot></slot></h1>
                    <div class="meta">
                        <slot name="channel"></slot>
                        <span>{{thread.updated_at}}</span>
                        by
                        <router-link :to="thread.creator">{{thread.creator.username}}</router-link>
                    </div>
                    <div class="body">
                        <slot name="body"></slot>
                    </div>
                </div>
                
            </div>
            <div class="reply-count">
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