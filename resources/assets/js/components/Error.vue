<template>
    <div class="notification is-danger">
        <button @click="$emit('close')" class="delete"></button>
        {{ errorMessage }}
    </div>
</template>

<script>
    export default {
        props: ['errorMessage']
    }
</script>