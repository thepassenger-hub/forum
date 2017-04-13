<template>
    <section class="section">
        <breadcrumbs :path="path.breadcrumbs"></breadcrumbs>
        <div class="container">
             <h1 class="title">Choose a channel.</h1>
            <channel v-for="channel in channels" :key="channel.name" @clicked="goToThreads(channel.slug)" >{{channel.name}}</channel>
        </div>
    </section>
</template>

<script>
    import Channel from '../models/Channel';
    import Path from '../models/Path';
    export default {
        data() {
            return {
                channels: [],
                path: new Path(this.$route.path)
            }
        },

        methods: {
            getChannels(){
                var vm = this;

                axios.get('/channels')
                     .then(response => {
                         if (response.data) response.data.forEach(channel => {
                             vm.channels.push(new Channel(channel));
                         });
                     })
                     .catch(error => console.log(error));
            },

            goToThreads(channelPath){
                this.$router.push({ path: '/'+channelPath});
            }
        },

        components: {
            'channel': require('../components/Channel.vue'),
            'breadcrumbs': require('../components/Breadcrumbs.vue')

        },

        created(){
            this.getChannels();
        }
    }
</script>
