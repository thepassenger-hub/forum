<template>
    <article class="media">
        <figure class="media-left">
            <p class="image is-64x64">
            <img src="http://bulma.io/images/placeholders/128x128.png">
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <slot name="username"></slot>
                <slot name="body"></slot>
                <small><a>Like</a> · <a>Reply</a> · 3 hrs</small>
            </p>
            </div>
        </div>
    </article>





</template>

<script>
    export default {

    }
</script>