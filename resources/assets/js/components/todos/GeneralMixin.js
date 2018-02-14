export default {
  props: {
    uniqueId: {type: Number, default: 0},
    showHeader: {type: Boolean, default: true},
    showContent: {type: Boolean, default: true},
  },
  methods: {
    foo: function () {
      console.log('foo')
    }
  }
}
