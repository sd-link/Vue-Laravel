<template>
    <div v-html="content" class="slide-text" :class="contentClass">
    </div>
</template>

<script>
    export default {
        props: {
            contentClass: {type: String, default: 'white'},
            content: {type: String, default: ''}
        }
    }
</script>
