<template>
    <div v-if="content" class="slide-image" style="display: flex; flex-direction: row; alignItems: center;" :style="{'justifyContent': imagePosition}">
        <img :src="content" :class="[imageResponsive ? 'img-responsive' : '' , contentClass]">
    </div>
</template>

<script>
    export default {
        props: {
            contentClass: {type: String, default: ''},
            imageResponsive: {type: Boolean, default: true},
            imagePosition: {type: String, default: 'center'},
            content: {type: String, default: ''}
        }
    }
</script>
