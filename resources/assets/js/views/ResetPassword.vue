<template>
    <div class="column is-9">
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    <h4 class="title is-4">Reset Password</h4>
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="email" placeholder="Email" v-model="form.email">
                    </p>
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="New password" v-model="form.password">
                    </p>
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Confirm password" v-model="form.password_confirmation">
                    </p>
                </div>
                <div class="field">
                    <p class="control">
                        <button class="button is-primary" @click="resetPassword">Reset Password</button>
                    </p>
                </div>
            </div>
            <div class="column is-5">
                <transition name="fade">
                    <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
                </transition>
                <transition name="fade">
                    <success v-if="successMessage" :successMessage="successMessage" @close="successMessage = false"></success>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from '../models/Form';
    import showNotificationsMixin from '../mixins/showNotificationsMixin';

    export default {
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
                    password_confirmation: '',
                    token: this.$route.params.token
                }),
                errorMessage: false,
                successMessage: false
            }
        },
        mixins: [showNotificationsMixin],
        methods: {
            resetPassword() {
                this.form.post('/password/reset')
                    .then(response => {
                        this.showSuccess(response);
                        this.form = new Form(this.form.originalData);
                    })
                    .catch(error => {
                        this.showError(error);
                        let data = this.form.originalData;
                        data['email'] = this.form.email;
                        this.form = new Form(data);
                    })
            }
        },
        created() {
            this.$root.path.update(this.$route.path);
        },
        components: {
            'error': require('../components/Error.vue'),
            'success': require('../components/Success.vue')
            
        }
    }
</script>