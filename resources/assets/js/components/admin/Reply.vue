<template>
    <div class="replies columns">
        <article class="media column is-10">
            <div class="media-content">
                <div class="content">
                    <p class="reply-creator">
                        <router-link :to="'/@'+reply.creator.username">{{reply.creator.username}}</router-link>
                    </p>
                    <span class="created-at">{{reply.createdAt | fromNow}}</span>
                    <p class="reply-body" v-html="markdown(truncate)"></p>
                </div>
            </div>
            <confirmation-modal v-if="confirm" @confirm="$emit('delete', reply.id); confirm = false" @close="confirm = false">
                {{confirmationMessage}}
            </confirmation-modal>
        </article>
        <div class="column is-2">
            <button class="reply-admin-delete button is-danger" @click="askConfirmationDelete">
                Delete
            </button>
        </div>

        <hr>
    </div>





</template>

<script>
    export default {
        
        props: ['reply'],
        data() {
            return {
                confirm: false
            }
        },
        methods: {
            markdown(text){
                return marked(text, {sanitize: true});
            },
            askConfirmationDelete() {
                this.confirmationMessage = 'Are you sure you want to delete this reply?';
                this.confirm = true;
            }
        },
        computed: {
            truncate() {
                if (this.reply.body.length <= 100) return this.reply.body;
                return this.reply.body.substring(0, 100) + '...';
            }
        },
        components: {
            'confirmationModal': require('../ConfirmationModal.vue'),
        }
    }
</script>