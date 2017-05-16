<template>
    <div class="thread columns">
        <article class="media column is-10">
            <div class="media-content">
                <div class="content">
                    <p class="title is-4 thread-title" @click="goToThread"><a>{{thread.title}}</a></p>
                    <span class="created-at">{{thread.updated_at | fromNow}}</span>
                    by
                    <strong class="created-by">
                        <router-link :to="'/@'+thread.creator.username">{{thread.creator.username}}</router-link>
                    </strong>
                    <p class="thread-body" v-html="markdown(truncate)"></p>
                </div>
            </div>
            <confirmation-modal v-if="confirm" @confirm="$emit('delete', thread.slug); confirm = false" @close="confirm = false">
                {{confirmationMessage}}
            </confirmation-modal>
        </article>
        <div class="column is-2">
            <button class="thread-admin-delete button is-danger" @click="askConfirmationDelete">
                Delete
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: ['thread'],
        data() {
            return {
                confirm: false
            }
        },
        methods: {
            markdown(text){
                return marked(text, {sanitize: true});
            },
            goToThread() {
                this.$router.push({ path: '/' + this.thread.channel.slug + '/' + this.thread.slug});
            },
            askConfirmationDelete() {
                this.confirmationMessage = 'Are you sure you want to delete this thread?';
                this.confirm = true;
            }
        },
        computed: {
            truncate() {
                if (this.thread.body.length <= 200) return this.thread.body;
                return this.thread.body.substring(0, 200) + '...';
            }
        },
        components: {
            'confirmationModal': require('../ConfirmationModal.vue'),
        }
    }
</script>