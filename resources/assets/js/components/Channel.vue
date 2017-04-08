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
                    <p>
                        Descrizione canale qua.
                    </p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
    export default {

    }
</script>