<template>
    <div class="column is-9">
        <h1 class="title">Choose a channel.</h1>
        <channel v-for="channel in channels" :key="channel.name" @clicked="goToThreads(channel.slug)" >
            <h1 slot="name">{{channel.name}}</h1>
            <p slot="description">{{channel.description}}</p>
        </channel>
    </div>
</template>

<script>
    import getChannelsMixin from '../mixins/GetChannelsMixin';

    export default {

        mixins:[getChannelsMixin],
        data() {
            return {
                channels: [],
            }
        },
        created(){
            this.getChannels();            
            this.$root.path.update(this.$route.path);
            
        },
        methods: {
            goToThreads(channelPath){
                this.$router.push({ path: '/'+channelPath});
            }
        },

        components: {
            'channel': require('../components/Channel.vue'),
        }
    }
</script>
