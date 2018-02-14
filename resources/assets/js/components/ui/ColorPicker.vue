<template>
    <div class="input-group colorpicker-component">
        <input type="text" v-model="color" class="form-control"/>
        <span class="input-group-addon"><i></i></span>
    </div>
</template>

<script>
    export default {
        props: {
            value: {type: String},
        },
        data() {
            return {
                color: this.value
            }
        },
        mounted: function() {
            var self = this
            $(self.$el).colorpicker()

            $(self.$el).on('changeColor', function(event) {
                self.color = $(self.$el).colorpicker('getValue')
                self.$emit('input', self.color)
            })

        },
        beforeDestroy: function() {
            // $(this.$el).colorpicker('hide').colorpicker('destroy')
        }
    }
</script>
