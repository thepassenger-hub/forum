<template>
    <div class="notification is-success">
        <button class="delete" @click="$emit('close')"></button>
        {{successMessage}}
    </div>
</template>

<script>
    export default {
        props: ['successMessage']
    }
</script>