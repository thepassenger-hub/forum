<template>
    <div class="column is-9">
        <form @submit.prevent="sendPost">
            <div class="field">
                <label class="label">Titolo</label>
                <p class="control">
                    <input v-model="form.title" class="input" type="text" placeholder="Text input" name="title" required>
                </p>
            </div>
            <div class="field">
                <label class="label">Pick a channel</label>
                  <p class="control">
                    <span class="select">
                    <select name="channel" v-model="channel" required>
                        <option v-for="channel in $root.channels" :value="channel.slug">{{channel.name}}</option>
                    </select>
                    </span>
                </p>
            </div>
            <div class="field">
                <label class="label">Testo</label>
                <p class="control">
                    <textarea v-model="form.body" class="textarea" placeholder="Textarea" name="body"></textarea>
                </p>
            </div>
            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary" type="submit">Submit</button>
                </p>
                <p class="control">
                    <button class="button is-link" @click="$router.push({name: 'home'})">Cancel</button>
                </p>
            </div>
        </form> 
        <transition name="fade">
            <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
        </transition>
    </div>        
</template>

<script>
    import Form from '../models/Form';
    import isLoggedMixin from '../mixins/IsLoggedMixin';
    import showNotificationsMixin from '../mixins/showNotificationsMixin';

    export default {
        data(){
            return {
                channel: null,
                form: new Form({
                    title: '',
                    description: '',
                    body: ''
                }),
                errorMessage: false
            }
        },
        mixins:[isLoggedMixin, showNotificationsMixin],
        beforeRouteEnter: (to, from, next) => {
            next(vm => {
                vm.checkIfLogged()
                    .then(response => response ? next() : next('/sign-in'))                    
                    .catch(error => next('/'));
            });
        },
        created(){
            this.$root.path.update(this.$route.path);
        },
        methods: {
            sendPost(){
                var vm = this;
                this.form.post('/channels/'+this.channel+'/threads')
                    .then(response => vm.$router.push({path: '/'+this.channel+'/'+response}))
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.showError(out);
                    });
            }
        },
        components: {
            'error': require('../components/Error.vue')
        }
    }
</script>