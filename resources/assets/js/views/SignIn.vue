<template>
    <div class="column is-9">
        <div class="columns">
            <div class="column is-half">
                <div class="field">
                    <h1 class="title">Log In</h1>
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="email" placeholder="Email" id="login-email" v-model="login.email">
                    </p>
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Password" id="login-password" v-model="login.password">
                    </p>
                </div>

                <div class="field">
                    <p class="control">
                        <label class="checkbox">
                            <input type="checkbox" v-model="login.remember" :false-value="empty" >
                            Remember me
                        </label>
                    </p>
                </div>

                <div class="field is-grouped">
                    <p class="control">
                        <button class="button is-primary" @click="postLoginForm">Login</button>
                    </p>
                    <p class="control">
                        <button class="button is-link" 
                        @click="forgotPassword = true; $scrollTo('#forgot-password', {'offset': -30});">Forgot your password?</button>
                    </p>
                </div>


            </div>

            <div class="column is-half">
                <div class="field">
                    <h1 class="title">Register New Account</h1>
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="text" placeholder="Username" id="register-username" v-model="register.username" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="email" placeholder="Email" id="register-email" v-model="register.email" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Password" id="register-password" v-model="register.password" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Verify Password" 
                            id="register-password-confirmation" v-model="register.password_confirmation" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field is-grouped">
                    <p class="control">
                        <button class="button is-primary" @click="postRegisterForm">Register</button>
                    </p>
                </div>
            </div>
            
        </div>
        <transition name="fade">
            <div id="forgot-password">
                <div v-if="forgotPassword">
                    <div class="field">
                        <h4 class="title is-4">Reset Password</h4>
                    </div>
                    <div class="field">
                        <p class="control">
                            <input type="email" class="input" placeholder="Email address" v-model="reset.email">
                        </p>
                    </div>
                    <div class="field">
                        <p class="control">
                            <button class="button is-primary" @click="resetPassword">Send Password reset link</button>
                        </p>
                    </div>
                </div>
            </div>
        </transition>
        
        <div id="messages">
            <transition name="fade">
            <success v-if="successMessage" :successMessage="successMessage" @close="successMessage = false"></success>
            </transition>
            <transition name="fade">
                <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
            </transition>
        </div>
    </div>
</template>

<script>
    import Form from '../models/Form';
    import showNotificationsMixin from '../mixins/showNotificationsMixin';

    export default {
        data(){
            return {
                login: new Form({
                    password: '',
                    email: '',
                    remember: ''
                }),
                register: new Form({
                    password: '',
                    email: '',
                    username: '',
                    password_confirmation: ''
                }),
                reset: new Form({
                    email: ''
                }),
                empty: "",
                forgotPassword: false,
                errorMessage: false,
                successMessage: false
                
            }
        },
        mixins: [showNotificationsMixin],
        created() {
            this.$root.path.update(this.$route.path);
        },
        methods: {
            postLoginForm(){
                var vm = this;
                this.login.post('/login')
                    .then(response => {
                        vm.$root.user = response.user;
                        vm.$router.back();
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('#messages', {'offset': -30});
                        
                    });
            },
            postRegisterForm(){
                var vm = this;
                if (this.register.password != this.register.password_confirmation) return;
                this.register.post('/register')
                    .then(response => {
                        vm.$root.user = response.user;
                        vm.$router.push('/');
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                        this.$scrollTo('#messages', {'offset': -30});
                    });
            },
            resetPassword() {
                this.reset.post('/password/email')
                    .then(response => {
                        this.showSuccess(response);   
                        this.reset.reset();      
                            
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);                        
                    });
            }
        },
        components: {
            'error': require('../components/Error.vue'),
            'success': require('../components/Success.vue')
        }
        
    }
</script>