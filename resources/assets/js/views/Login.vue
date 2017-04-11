<template>
    <section class="section">
        <div class="container">
            <div class="field">
                <label class="label">Email</label>
                <p class="control">
                    <input class="input" type="email" placeholder="Email input" v-model="form.email">
                </p>
                <!--<p class="help is-danger">This email is invalid</p>-->
            </div>

            <div class="field">
                <label class="label">Password</label>
                <p class="control">
                    <input class="input" type="password" placeholder="Email input" v-model="form.password">
                </p>
                <!--<p class="help is-danger">This email is invalid</p>-->
            </div>

            <div class="field">
                <p class="control">
                    <label class="checkbox">
                        <input type="checkbox" v-model="form.rememberMe">
                        Remember me
                    </label>
                </p>
            </div>

            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary" @click="sendPost">Login</button>
                </p>
                <p class="control">
                    <button class="button is-link">Forgot your passoword?</button>
                </p>
            </div>
        </div>
    </section>
</template>

<script>
    import Form from '../models/Form';
    export default {
        data(){
            return {
                form: new Form({
                    password: '',
                    email: '',
                    rememberMe: false
                })
            }
        },
        methods: {
            sendPost(){
                var vm = this;
                this.form.post('/login')
                    .then(response => {
                        console.log(response)
                    })
                    .catch(error => console.log(error));
            }
        }
    }
</script>