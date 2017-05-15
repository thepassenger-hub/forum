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
                <button class="user-admin-ban button is-danger">
                    Ban
                </button>
            </div>
            <div class="column is-9">
                <div class="field has-addons">
                <p class="control">
                    <input v-model="days" class="input suspension-time-input"
                     type="number" placeholder="How many days?">
                </p>
                <p class="control">
                    <button class="user-admin-suspend button is-warning" @click="$emit('suspend', days)">
                        Suspend
                    </button>
                </p>
            </div>
            </div>
            
            
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                days: ''
            }
        }
    }
</script>