<template>
    <div class="column is-9">
        <div class="tabs is-centered is-boxed">
            <ul>
                <li :class="{ 'is-active': tabs[0].isActive }">
                    <a  @click="selectTab(tabs[0].name)">{{ tabs[0].name }}</a>
                </li>
                <li :class="{ 'is-active': tabs[1].isActive }">
                    <a  @click="selectTab(tabs[1].name)">{{ tabs[1].name }}</a>
                </li>
            </ul>
        </div>
        <view-profile v-if="tabs[0].isActive" :profile="profile"></view-profile>
        <activity v-if="tabs[1].isActive" :profile="profile"></activity>
        
        
    </div>
</template>

<script>
    import isLoggedMixin from '../mixins/IsLoggedMixin';

    export default {
        data() {
            return {
                tabs: [
                        {name: 'View', isActive: true},
                        {name: 'Activity', isActive: false}
                    ],
                profile: {}
            }
        },
        mixins:[isLoggedMixin],
        created() {
            this.$root.path.update(this.$route.path);
            let username = this.$route.params.username;
            this.getProfile(username);
        },
        watch: {
            '$route': function(){
                this.$root.path.update(this.$route.path);
                let username = this.$route.params.username;
                this.getProfile(username);
            }
        },
        methods: {
            getProfile(username) {
                axios.get('/profile/user/' + username)
                     .then(response => this.profile = response.data)
                     .catch(error => console.log(error.response.data))
            },
            selectTab(tabName) {
                this.tabs.forEach(tab => {
                    tab.isActive = tab.name === tabName ? true : false;
                });
            },
            
        },
        components: {
            'viewProfile': require('../components/Profile/ViewProfile'),
            'activity': require('../components/Profile/Activity')            
        }
    }
</script>