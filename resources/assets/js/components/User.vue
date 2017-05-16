<template>
    <div class="user columns">
        <article class="media column is-6">
            <div class="media-content">
                <div class="content">
                    <p class="username is-4 thread-title">{{user.username}}</p>
                    <p class="joined-at">{{user.created_at | fromNow}}</p>
                    <p class="status">{{user.status.status | capitalize}} 
                        <span v-if="user.status.status === 'banned'">{{user.status.until | bannedFor}}</span>
                    </p>
                    
                </div>
            </div>
        </article>
        <div class="suspend-wrapper columns">
            <div class="column is-3">
                <button class="user-admin-ban button is-danger" v-if="user.status.status === 'active'" @click="days=6000; askConfirmationSuspend()">
                    Ban
                </button>
                <button class="user-admin-enable button is-success" @click="askConfirmationEnable" v-else>
                    Enable
                </button>
            </div>
            <div class="column is-9">
                <div class="field has-addons">
                    <p class="control">
                        <input v-model="days" class="input suspension-time-input"
                        type="number" placeholder="How many days?">
                    </p>
                    <p class="control">
                        <button class="user-admin-suspend button is-warning" @click="askConfirmationSuspend">
                            Suspend
                        </button>
                    </p>
                </div>
            </div>
        </div>
        <confirmation-modal v-if="confirmation" @confirm="$emit(action, user.username, days); resetData();
        " @close="resetData">
            {{confirmationMessage}}
        </confirmation-modal>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                days: '',
                action: '',
                confirmationMessage: '',
                confirmation: false,
            }
        },
        methods: {
            askConfirmationSuspend() {
                this.action = 'suspend';
                this.confirmationMessage = 'Are you sure you want to suspend ' + this.user.username + ' for ' + this.days + ' days?';
                this.confirmation = true;
            },
            askConfirmationEnable() {
                this.action = 'enable';
                this.confirmationMessage = 'Are you sure you want to enable ' + this.user.username + '\'s account?';
                this.confirmation = true;
            },
            resetData(){
                this.action = null;
                this.confirmation = false;
                this.days = '';
                this.confirmationMessage = '';
            }
        },
        components: {
            'confirmationModal': require('./ConfirmationModal')
        }
    }
</script>