<template>
    <div v-if="form">
        <div class="columns">
            <div class="column is-9">
                <article class="media">
                    <figure class="image is-128x128">
                        <img :src="this.imageSrc || this.form.avatar">
                    </figure>
                    <div class="media-content">
                        <div class="content" id="profile-username">
                            <label class="button is-primary" id="add-new-avatar-label" for="add-new-avatar">Browse...</label>
                            <input type="file" class="input" id="add-new-avatar" @change="newAvatar">
                            <button type="button" id="submit-avatar" class="button is-default" @click="postAvatar">Upload Avatar</button>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        <div class="field">
            <label class="label">Name</label>
            <p class="control">
                <input type="text" class="input" id="profile-name-input" placeholder="Name" v-model="form.name">
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
        <hr>
        <div>
            CHANGE PASSWORD HERE
        </div>
    </div>
</template>

<script>
    import Form from '../../models/Form';

    export default {
        props: ['profile'],
        data() {
            return {
                form: new Form({
                    name: this.profile.name,
                    bio: this.profile.bio,
                    location: this.profile.location,
                    avatar: this.profile.avatar
                }),
                avatar: null,
                imageSrc: null
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

            newAvatar(event) {
                let files = event.target.files;
                if (files.length) this.avatar = files[0];
                var file = event.target.files[0];

                var reader = new FileReader();
                var vm = this;

                reader.onload = function(e) {
                    vm.imageSrc = e.target.result;
                }

                reader.readAsDataURL(file);
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