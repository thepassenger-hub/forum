<template>
    <div class="container" v-if="form">
        <div class="field">
            <figure class="image is-128x128">
                <img :src="form.avatar">
            </figure>
            <p class="control">
                <input type="file" class="input" @change="newAvatar">
            </p>
            <p class="control">
                <button type="button" class="button is-default" @click="postAvatar">Upload Avatar</button>
            </p>
        </div>
        <div class="field">
            <label class="label">Name</label>
            <p class="control">
                <input type="text" class="input" id="profile-name-input" placeholder="Name" v-model="form.name">
            </p>
        </div>

        <div class="field">
            <label class="label">Gender</label>
            <p class="control">
                <label class="radio">
                    <input type="radio" value="m" v-model="form.gender">
                    Male
                </label>
                <label class="radio">
                    <input type="radio" value="f" v-model="form.gender">
                    Female
                </label>
            </p>
        </div>
        <div class="field">
            <label class="label">About me</label>
            <p class="control">
                <textarea class="textarea" placeholder="About me" v-model="form.bio"></textarea>
            </p>
        </div>
        <div class="field">
            <label class="label">Location</label>
            <p class="control">
                <input type="text" class="input" id="profile-name-input" placeholder="Location" v-model="form.location">
            </p>
        </div>
        <div class="field is-grouped">
            <p class="control">
                <button class="button is-primary" @click="submitChanges">Save changes</button>
            </p>
            <p class="control">
                <button class="button is-link" @click="resetFields">Reset</button>
            </p>
        </div>
    </div>
</template>

<script>
    import Form from '../models/Form';

    export default {
        props: ['profile'],
        data() {
            return {
                form: new Form({
                    name: this.profile.name,
                    gender: this.profile.gender,
                    bio: this.profile.bio,
                    location: this.profile.location,
                    avatar: this.profile.avatar
                }),
                avatar: null
            }
        },

        methods: {
            submitChanges() {
                this.form.post('/profile')
                    .then(response => this.$emit('changesSaved'))
                    .catch(error => console.log(error));
            },

            resetFields() {
                this.form = new Form(this.form.originalData);
            },

            newAvatar($event) {
                let files = $event.target.files;
                if (files.length) this.avatar = files[0];
            },

            postAvatar(){
                let data = new FormData()
                data.set('avatar', this.avatar)
                axios.post('/profile/avatar', data)
                    .then(response => console.log(response))
                    .catch(error => console.log(error));
            }

        }
        
    }
</script>