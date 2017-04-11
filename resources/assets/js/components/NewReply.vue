<template>
    <article class="media">
        <figure class="media-left">
            <p class="image is-64x64">
             <img src="http://bulma.io/images/placeholders/128x128.png">
            </p>
        </figure>
        <div class="media-content">
            <div class="field">
                <p class="control">
                        <textarea v-model="form.body" class="textarea" placeholder="Textarea" name="body"></textarea>
                </p>
            </div>
            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary" type="button" @click="sendPost">Submit</button>
                </p>
                <p class="control">
                    <button class="button is-default" type="button" @click="form.reset()">Clear</button>
                </p>
            </div>
        </div>
    </article>    
</template>

<script>
    import Form from '../models/Form';
    import isLoggedMixin from '../mixins/IsLoggedMixin';

    export default {
        props: ['thread'],
        data(){
            return {
                form: new Form({
                    body: ''
                })
            }
        },

        mixins:[isLoggedMixin],

        methods: {
            sendPost(){
                var vm = this;
                this.form.post('/threads/'+this.thread+'/replies')
                    .then(response => {
                        vm.$emit('posted');
                        vm.$emit('close');
                    })
                    .catch(error => console.log(error));
            }
        }
    }
</script>