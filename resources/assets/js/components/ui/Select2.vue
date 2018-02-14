<template>
    <select class="form-control" v-model="selected">
        <option value="0">Select one</option>
    </select>
</template>

<script>
export default {
    props: ['initOptions', 'value'],
    data: function data() {
        return {
            selected: this.value,
            options: this.initOptions
        }
    },
    mounted: function () {
      var vm = this
      $(this.$el)
        .select2({ data: this.options })
        .val(this.value)
        .trigger('change')
        // emit event on change.
        .on('change', function () {
          vm.$emit('input', this.value)
        })
    },
    watch: {
      value: function (value) {
        // update value
        $(this.$el).val(value).trigger('change');
      },
      options: function (options) {
        // update options
        $(this.$el).select2({ data: options })
      }
    },
    destroyed: function () {
      $(this.$el).off().select2('destroy')
    }
}
</script>
<style lang="scss">
</style>
