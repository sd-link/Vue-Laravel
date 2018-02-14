<template>
  <div role="dialog"
    :class="['modal',!showBackdrop?'no-backdrop':'']"
    @click="backdrop&&action(false,1)"
  >
    <div
      :class="['modal-dialog',{'modal-lg':large,'modal-sm':small}]"
      role="document"
      @click.stop="action(null)"
      :style="{width: defaultHeightPrepared||null}"
    >
      <div ref="modalContent" class="modal-content">
        <slot name="modal-header">
          <div class="modal-header" :class="{'grabbable':draggable}">
            <button type="button" class="close" @click="action(false,2)"><span>&times;</span></button>
            <h4 class="modal-title"><slot name="title">{{title}}</slot></h4>
          </div>
        </slot>
        <slot name="modal-body">
          <div class="modal-body">
            <slot></slot>
          </div>
        </slot>
        <slot name="modal-footer">
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="action(false,3)">{{ cancelText }}</button>
            <button type="button" class="btn btn-primary" @click="action(true,4)">{{ okText }}</button>
          </div>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
// This component based on https://github.com/wffranco/vue-strap
// https://github.com/wffranco/vue-strap/blob/master/src/Modal.vue
import {getScrollBarWidth} from 'utils/utils.js'
import $ from 'jquery'
import 'jquery-ui/ui/version'
import 'jquery-ui/ui/plugin'
import 'jquery-ui/ui/widget'
import 'jquery-ui/ui/widgets/mouse'
import 'jquery-ui/ui/widgets/resizable'
import 'jquery-ui/ui/widgets/draggable'

export default {
  props: {
    backdrop: {type: Boolean, default: false},
    showBackdrop: {type: Boolean, default: true},
    callback: {type: Function, default: null},
    cancelText: {type: String, default: 'Close'},
    large: {type: Boolean, default: false},
    okText: {type: String, default: 'Save changes'},
    small: {type: Boolean, default: false},
    title: {type: String, default: ''},
    value: {type: Boolean, required: true},

    width: {default: null},
    height: {default: null},
    top: {default: null},
    left: {default: null},

    defaultWidth: {default: null},
    defaultHeight: {default: null},

    draggable: {default: false}
  },
  data () {
    return {
      transition: false,
      val: null,
      currentPositionData: {
        width: null,
        height: null,
        top: 0,
        left: 0
      }
    }
  },
  computed: {
    optionalWidth () {
      return this.prepareMetricValue(this.width)
    },
    optionalHeight () {
      return this.prepareMetricValue(this.height)
    },
    defaultHeightPrepared () {
      return this.prepareMetricValue(this.defaultWidth)
    }
  },
  watch: {
    transition (val, old) {
      if (val === old) { return }
      const el = this.$el
      const body = document.body
      if (val) {//starting
        if (this.val) {
          el.querySelector('.modal-content').focus()
          el.style.display = 'block'
          setTimeout(() => el.classList.add('in'), 0)
          body.classList.add('modal-open')
          if (getScrollBarWidth() !== 0) {
            body.style.paddingRight = getScrollBarWidth() + 'px'
          }
        } else {
          el.classList.remove('in')
        }
        _.delay(() => {
          this.transition = false
        }, 100)
      } else {//ending
        this.$emit(this.val ? 'opened' : 'closed')
        if (!this.val) {
          el.style.display = 'none'
          body.style.paddingRight = null
          body.classList.remove('modal-open')
        }
      }
    },
    val (val, old) {
      let $contentEl = $(this.$refs.modalContent)
      this.$emit('input', val)
      if (old === null ? val === true : val !== old) this.transition = true

      if (val && !old) {
        this.setCurrentPositionData({
          width: this.width,
          height: this.height,
          top: this.top,
          left: this.left
        })

        $contentEl.css({
          'top': this.top,
          'left' : this.left,
          'width' : this.width||'',
          'height' : this.height||(this.defaultHeight?this.defaultHeight:'')
        })

        if(this.draggable) {
          $contentEl.resizable({
            minHeight: 300,
            minWidth: 400,
            handles: "se, sw",
            stop: ( event, ui ) => {
              let { size: { width: width=null, height: height=null } } = ui
              this.currentPositionData.width = width
              this.currentPositionData.height = height
            },
          })
          $contentEl.draggable({
            handle: '.modal-header',
            stop: ( event, ui ) => {
              let { position: { top: top=0, left: left=0 } } = ui
              this.currentPositionData.top = top
              this.currentPositionData.left = left
            }
          })
        }
      }
      if (!val && old) {
        if(this.draggable) {
          $contentEl.resizable('destroy')
          $contentEl.draggable('destroy')
        }
      }
    },
    value (val, old) {
      if (val !== old) this.val = val
    }
  },
  methods: {
    action (val,p) {
      if (val === null) { return }
      if (val && this.callback instanceof Function) this.callback()
      this.$emit(val ? 'ok' : 'cancel',p)
      this.val = val || false
    },
    setCurrentPositionData (data) {
      this.currentPositionData = data
    },
    prepareMetricValue (val) {
      if (val === null) {
        return null
      } else if (Number.isInteger(val)) {
        return val + 'px'
      }
      return val
    }
  },
  mounted () {
    this.val = this.value
  }
}
</script>
<style>
.modal {
  transition: all 0.01s ease;
}
.modal.in {
  background-color: rgba(0,0,0,0.1);
}
.modal.in.no-backdrop {
  background-color: rgba(0,0,0,0.01);
}
</style>
