import Channel from '../models/Channel';

var getChannelsMixin = {
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
        }
    }
}

export default getChannelsMixin;

