<template>
    <div class="thread">
        <article class="media">
            <figure class="media-left is-hidden-mobile">
                <p class="image is-64x64" v-if="thread.creator.profile">
                    <img :src="thread.creator.profile.avatar">
                </p>
            </figure>
            <div class="media-content" :class="{'thread-if-reply-counter': thread.replies_count !== undefined}">
                <div class="content">
                    <p class="title is-4 thread-title" @click="$emit('clicked')"><slot></slot></p>
                    <slot name="channel"></slot>
                    <span class="created-at">{{thread.updated_at | fromNow}}</span>
                    by
                    <strong class="created-by">
                        <router-link :to="'/@'+thread.creator.username">{{thread.creator.username}}</router-link>
                    </strong>
                    <slot name="body" v-if="!edit"></slot>
                    <div class="edit-thread-form" v-if="edit">
                        <div class="field">
                            <textarea class="textarea" name="thread-message" v-model="threadMessage" required></textarea>
                        </div>
                        <div class="field is-grouped">
                            <p class="control">
                                <button class="button is-primary" id="edit-thread-message" type="button" 
                                    @click="editThread(threadMessage, thread.slug); edit = false; thread.body = threadMessage">
                                        Update your reply
                                </button>
                            </p>
                            <p class="control" id="clear-form-button">
                                <button class="button is-default" type="button"
                                    @click="threadMessage = thread.body; edit = false;">Clear</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="thread-admin" v-if="$root.user.isAdmin">
                <button class="thread-admin-delete button is-danger" @click="confirmRemove = true">
                    Remove
                </button>
            </div>
            <confirmation-modal v-if="confirmRemove" @confirm="removeThread(thread.slug); confirmRemove = false" 
                @close="confirmRemove = false">
                <p class="control">
                    Are you sure you want to remove this thread?
                </p>
            </confirmation-modal>
            <div class="thread-modifiers" v-if="thread.creator.username === $root.user.username">
                <a class="thread-edit" @click="edit = true;">
                    <span class="icon">
                        <i class="fa fa-pencil"></i>
                    </span>
                </a>
                <a class="thread-delete" @click="confirm = true">
                    <span class="icon">
                        <i class="fa fa-trash-o"></i>
                    </span>
                </a>
                
            </div>
            <confirmation-modal v-if="confirm" @confirm="deleteThread(thread.slug); confirm = false" 
                @close="confirm = false">
                <p class="control">
                    Are you sure you want to delete your thread?
                </p>
            </confirmation-modal>
            <div class="reply-count replies-count is-hidden-mobile">
                {{thread.replies_count}}
            </div>
        </article>
        <div id="messages">
            <transition name="fade">
                <error v-if="errorMessage" :errorMessage="errorMessage" @close="errorMessage = false"></error>
            </transition>
        </div>
    </div>
</template>

<script>
    import showNotificationsMixin from '../mixins/showNotificationsMixin';
    export default {
        
        props: ['thread'],
        data() {
            return {
                confirm: false,
                confirmRemove: false,
                threadMessage: this.thread.body,
                edit: false,
                errorMessage: false,
                successMessage: false
            }
        },
        mixins: [showNotificationsMixin],
        components: {
            'confirmationModal': require('./ConfirmationModal.vue'),
            'error': require('./Error.vue'),
            'success': require('./Success.vue')
        },
        methods: {
            removeThread(threadSlug){
                axios.delete('/admin/threads/' + threadSlug)
                    .then(response => {
                        if (this.$route.name === 'home') {
                            this.$parent.threads = [];
                            this.$parent.getLatestThreads();
                        } else {
                            this.$router.push({ name: 'home'});
                        };
                    })

                    .catch(error => {
                        this.showError(error);
                    });
            },
            editThread(threadMessage, threadSlug) {
                axios.patch('threads/'+threadSlug, {
                    body: threadMessage
                })
                .catch(error => {
                    this.showError(error);
                });
            },

            deleteThread(threadSlug) {
                axios.delete('threads/'+threadSlug)
                    .then(response => {
                        this.$router.push({ name: 'home'});
                    })
                    .catch(error => {
                        this.showError(error);
                    })
            }
        }
    }
</script>