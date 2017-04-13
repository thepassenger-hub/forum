<template>
    <ul class="breadcrumb">
        <router-link v-for="route in path" tag="li" :to="route.path" :key="route"><a>{{route.name}}</a></router-link>
    </ul>
</template>

<script>
    export default {
        props: ['path']
    }
</script>

