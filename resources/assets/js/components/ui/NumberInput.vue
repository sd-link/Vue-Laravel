<template>
    <div class="spinner">
        <input :id="id" type="number" v-model="value" :min="min" :max="max" class="form-control"/>
    </div>
</template>

<script>
    export default {
        props: {
            id: {type: String},
            initValue: {type: Number},
            initMin: {type: Number, default: 1},
            initMax: {type: Number, default: 100},
            initEvent: {type: Boolean, default: false},
            initEventName: {type: String},
        },
        data() {
            return {
                value: this.initValue,
                min: this.initMin,
                max: this.initMax,
                event: this.initEvent,
                eventName: this.initEventName,
            }
        },
        mounted: function() {
        },
        beforeDestroy: function() {
        }
    }
</script>
