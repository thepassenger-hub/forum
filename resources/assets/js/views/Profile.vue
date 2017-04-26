<template>
    <div class="column is-9">
        <div class="tabs is-centered is-boxed" v-if="isMyProfile">
            <ul>
                <li :class="{ 'is-active': tabs[0].isActive }">
                    <a  @click="selectTab(tabs[0].name)">{{ tabs[0].name }}</a>
                </li>
                <li :class="{ 'is-active': tabs[1].isActive }">
                    <a  @click="selectTab(tabs[1].name)">{{ tabs[1].name }}</a>
                </li>
            </ul>
        </div>
        <view-profile v-if="tabs[0].isActive || !isMyProfile" :profile="profile"></view-profile>
        <edit-profile v-if="tabs[1].isActive && isMyProfile" :profile="profile" @changesSaved="changesSaved"></edit-profile>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tabs: [
                        {name: 'View', isActive: true},
                        {name: 'Edit', isActive: false}
                    ],
                profile: false
            }
        },
        watch: {
            $route() {
                this.getProfile();
                this.$root.path.update(this.$route.path);
            }
        },
        created() {
            this.$root.path.update(this.$route.path);
            this.getProfile();
        },
        methods: {
            getProfile() {
                axios.get('/profile/'+this.$route.params.username)
                     .then(response => this.profile = response.data)
                     .catch(error => console.log(error.response.data))
            },
            selectTab(tabName) {
                this.tabs.forEach(tab => {
                    tab.isActive = tab.name === tabName ? true : false;
                });
            },

            changesSaved() {
                this.getProfile();
                this.selectTab(this.tabs[0].name);
            }
            
        },
        computed: {
            isMyProfile() {
                return this.$root.username === (this.profile ? this.profile.user.username : '')
            }
        },
        components: {
            'viewProfile': require('../components/Profile/ViewProfile'),
            'editProfile': require('../components/Profile/EditProfile')
        }
    }
</script>