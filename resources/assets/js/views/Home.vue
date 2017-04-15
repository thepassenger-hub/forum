<template>
    <section class="section">
        <div class="container">
             <h1 class="title">Choose a channel.</h1>
            <channel v-for="channel in channels" :key="channel.name" @clicked="goToThreads(channel.slug)" >
                <h1 slot="name">{{channel.name}}</h1>
                <p slot="description">{{channel.description}}</p>
            </channel>
        </div>
    </section>
</template>

<script>
    import Channel from '../models/Channel';
    export default {
        data() {
            return {
                channels: [],
            }
        },
        created(){
            this.$root.path.subpath = this.$route.path;
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
        },

        created(){
            this.getChannels();
            this.$root.path.update(this.$route.path);
        }
    }
</script>
