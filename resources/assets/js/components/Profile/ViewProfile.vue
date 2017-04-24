<template>
    <div class="container">
        <figure class="image is-128x128">
                <img :src="profile.avatar">
        </figure>
        <h4 class="title is-4" ><span>Name:</span>
            <span v-if="profile.name" class="title is-4">{{profile.name}}</span>
            <span v-else> Not set Yet!</span>
        </h4>
        <h4 class="title is-4" ><span>About me:</span>
            <span v-if="profile.bio" class="title is-4">{{profile.bio}}</span>
            <span v-else> Not set Yet!</span>
        </h4>
        <h4 class="title is-4" ><span>Location:</span>
            <span v-if="profile.location" class="title is-4">{{profile.location}}</span>
            <span v-else> The internet.</span>
        </h4>
        <h4 class="title is-4" ><span>Threads:</span>
            <span v-if="profile.user"class="title is-4">{{profile.user.threads_count}}</span>
        </h4>
        <h4 class="title is-4" ><span>Replies:</span>
            <span v-if="profile.user" class="title is-4">{{profile.user.replies_count}}</span>
        </h4>
        <h4 class="title is-4" ><span>Member since:</span>
            <span v-if="profile.created_at" class="title is-4">{{registered}}</span>
        </h4>
        <!--<input v-else type="text" class="input" id="profile-name-input" placeholder="Name">-->
    </div>
</template>

<script>
    export default {
        props: ['profile'],
        computed: {
            registered(){
                return this.profile.created_at;
            }
        },
        filters: {
            gender($value) {
                return $value ==='m' ? 'Male' : 'Female';
            }
        }
    }
</script>