<template>
    <div class="column is-9">
        <div class="columns">
            <div class="column is-half">
                <div class="field">
                    <h1 class="title">Log In</h1>
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="email" placeholder="Email" v-model="login.email">
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Password" v-model="login.password">
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
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
                        <button class="button is-link">Forgot your password?</button>
                    </p>
                </div>
            </div>




            <div class="column is-half">
                <div class="field">
                    <h1 class="title">Register New Account</h1>
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="text" placeholder="Username" v-model="register.username" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>
                <div class="field">
                    <p class="control">
                        <input class="input" type="email" placeholder="Email" v-model="register.email" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Password" v-model="register.password" required>
                    </p>
                    <!--<p class="help is-danger">This email is invalid</p>-->
                </div>

                <div class="field">
                    <p class="control">
                        <input class="input" type="password" placeholder="Verify Password" v-model="register.password_confirmation" required>
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
    </div>
</template>

<script>
    import Form from '../models/Form';
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
                empty: ""
            }
        },
        created() {
            this.$root.path.update(this.$route.path);
        },
        methods: {
            postLoginForm(){
                var vm = this;
                this.login.post('/login')
                    .then(response => {
                        vm.$router.back();
                        vm.$root.username = response.user.username;
                    })
                    .catch(error => console.log(error));
            },
            postRegisterForm(){
                var vm = this;
                if (this.register.password != this.register.password_confirmation) return;
                this.register.post('/register')
                    .then(response => {
                        vm.$router.push('/');
                        vm.$root.isLogged = true;
                    })
                    .catch(error => console.log(error));
            }
        }
    }
</script>