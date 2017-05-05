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
        <div class="field">
            <label class="label">Change your password</label>
        </div>
        <div class="field">
            <input type="password" class="input" v-model="passwordForm.oldPassword" placeholder="Current password.">
        </div>
        <div class="field">
            <input type="password" class="input" v-model="passwordForm.password" placeholder="New password. Min 6 Chars.">
        </div>
        <div class="field">
            <input type="password" class="input" v-model="passwordForm.password_confirmation" placeholder="Confirm new password.">
        </div>
        <div class="field is-grouped">
            <p class="control">
                <button class="button is-danger" @click="changePassword">Change Password</button>
            </p>
            <p class="control">
                <button class="button is-link" 
                    @click="passwordForm.reset()">Reset</button>
            </p>
        </div>
        <transition name="fade">
            <success v-if="successMessage" :successMessage="successMessage" @close="successMessage = false"></success>
        </transition>
        <transition name="fade">
            <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
        </transition>
    </div>
</template>

<script>
    import Form from '../../models/Form';
    import showNotificationsMixin from '../../mixins/showNotificationsMixin';

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
                passwordForm: new Form({
                    oldPassword: null,
                    password: null,
                    password_confirmation: null
                }),
                avatar: null,
                imageSrc: null,
                errorMessage: false,
                successMessage: false
            }
        },
        mixins: [showNotificationsMixin],
        methods: {
            submitChanges() {
                this.form.patch('/profile')
                    .then(response => this.$emit('changesSaved'))
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                    });
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
                    .then(response => this.$emit('changesSaved'))
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('.button.is-danger');
                    });
            },

            changePassword(){
                this.passwordForm.post('/user/password')
                    .then(response => {
                        this.showSuccess(response);
                        this.passwordForm.reset();
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                    });
            }

        },
        components: {
            'error': require('../Error.vue'),
            'success': require('../Success.vue')
        }
        
    }
</script>