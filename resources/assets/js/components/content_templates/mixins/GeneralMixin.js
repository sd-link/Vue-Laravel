export default {
  props: {
    uniqueId: {type: Number, default: 0},
    showHeaders: {type: Boolean, default: true},
    showContent: {type: Boolean, default: true},
    containerPreview: {type: Boolean, default: true},
  },
  methods: {
    foo: function () {
      console.log('foo')
    }
  }
}
