<template>
    <section class="section">
        <form @submit.prevent="sendPost">
            <div class="field">
                <label class="label">Titolo</label>
                <p class="control">
                    <input v-model="form.title" class="input" type="text" placeholder="Text input" name="title" required>
                </p>
            </div>

            <div class="field">
                <label class="label">Descrizione</label>
                <p class="control">
                    <input v-model="form.description" class="input" type="text" placeholder="Text input" name="description" required>
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
                    <button class="button is-link" @click="$router.back()">Cancel</button>
                </p>
            </div>
        </form> 
    </section>        
</template>

<script>
    import Form from '../models/Form';
    import isLoggedMixin from '../mixins/IsLoggedMixin';

    export default {
        data(){
            return {
                channel: this.$route.params.channel,
                form: new Form({
                    title: '',
                    description: '',
                    body: ''
                })
            }
        },
        mixins:[isLoggedMixin],
        beforeRouteEnter: (to, from, next) => {
            next(vm => {
                vm.checkIfLogged()
                    .then(response => response ? next() : next('/login'))                    
                    .catch(error => next('/'+ vm.channel));
            });
      },
        methods: {
            sendPost(){
                var vm = this;
                this.form.post('/channels/'+this.channel+'/threads')
                    .then(response => vm.$router.back())
                    .catch(error => console.log(error));
            }
        }
    }
</script>