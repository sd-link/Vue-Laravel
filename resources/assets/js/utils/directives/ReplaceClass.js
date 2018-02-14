function handler (el, val, old) {
  el.className = val || ''
}

export default {
  bind: function(el, binding) {
    handler(el, binding.value, undefined)
  },
  update: function(el, binding) {
    handler(el, binding.value, binding.oldValue)
  }
}
