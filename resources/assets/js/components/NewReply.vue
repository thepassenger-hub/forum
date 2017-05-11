<template>
    <article class="media" id="new-reply-form">
        <figure class="media-left is-hidden-mobile">
            <p class="image is-64x64">
             <img :src="$root.user.profile.avatar">
            </p>
        </figure>
        <div class="media-content">
            <div class="field">
                <p class="control">
                        <textarea v-model="form.body" class="textarea" placeholder="Add a reply" name="body"></textarea>
                </p>
            </div>
            <div class="field is-grouped">
                <p class="control">
                    <button class="button is-primary" id="new-reply-button" type="button" @click="sendPost">Submit</button>
                </p>
                <p class="control" id="clear-form-button">
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
                    })
                    .catch(error => {
                        let out = '';
                        Object.keys(error).forEach(field => out += error[field] +'\n' );
                        this.$emit('error', out);
                    });
            }
        }
    }
</script>