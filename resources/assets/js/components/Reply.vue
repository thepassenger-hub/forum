<template>
    <article class="media replies">
        <figure class="media-left is-hidden-mobile">
            <p class="image is-64x64">
                <img :src="reply.creator.profile.avatar">
            </p>
        </figure>
        <div class="media-content">
            <div class="content">
                <slot name="username"></slot>
                <span class="created-at">{{reply.createdAt | fromNow}}</span>
                <slot name="body" v-if="!edit"></slot>
                <confirmation-modal v-if="confirmRemove" @confirm="$emit('remove', reply.id); confirmRemove = false" 
                    @close="confirmRemove = false">
                    <p class="control">
                        Are you sure you want to remove this reply?
                    </p>
                </confirmation-modal>
                <div class="edit-reply-form" v-if="edit">
                    <div class="field">
                        <textarea class="textarea" name="reply-message" v-model="replyMessage" required></textarea>
                    </div>
                    <div class="field is-grouped">
                        <p class="control">
                            <button class="button is-primary" id="new-reply-button" type="button" 
                                @click="$emit('edit', replyMessage, reply.id); edit = false; reply.body = replyMessage">
                                    Update your reply
                            </button>
                        </p>
                        <p class="control" id="clear-form-button">
                            <button class="button is-default" type="button"
                                @click="replyMessage = reply.body; edit = false;">Clear</button>
                        </p>
                    </div>
                </div>
                <small><a>Reply</a></small>
            </p>
            </div>
        </div>
        <div class="reply-admin" v-if="$root.user.isAdmin">
            <button class="thread-admin-delete button is-danger" @click="confirmRemove = true">
                Remove
            </button>
        </div>
        <div class="reply-modifiers" v-if="reply.creator.username === $root.user.username">
            <a class="reply-edit" @click="edit = true;">
                <span class="icon">
                    <i class="fa fa-pencil"></i>
                </span>
            </a>
            <a class="reply-delete" @click="confirm = true">
                <span class="icon">
                    <i class="fa fa-trash-o"></i>
                </span>
            </a>
            <confirmation-modal v-if="confirm" @confirm="$emit('delete', reply.id); confirm = false" 
                @close="confirm = false"></confirmation-modal>
        </div>
    </article>





</template>

<script>
    export default {
        
        props: ['reply'],
        data() {
            return {
                edit: false,
                replyMessage: this.reply.body,
                confirm: false,
                confirmRemove: false
            }
        },
        components: {
            'confirmationModal': require('./ConfirmationModal.vue')
        }
    }
</script>