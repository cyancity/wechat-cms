<template>
  <transition v-if="show" name="modal">
    <div class="modal" @click.self="clickMask">
      <div class="modal-dialog" :class="modalClass">
        <div class="modal-content">

          <div class="modal-header">
            <slot name="header">
              <a type="button" class="close" @click="cancel">
                <i class="ion-close-round"></i>
                <h4 class="modal-title">
                  <slot>
                    {{ title }}
                  </slot>
                </h4>
              </a>
            </slot>
          </div>

          <div class="modal-body">
            <slot></slot>
          </div>

          <div class="modal-footer" v-if="showFooter">
            <slot name="footer">
              <button type="button" :class="cancelClass" @click="cancel">{{ cancelText }}</button>
              <button type="button" :class="confirmClass" @click="confirm">{{ confirmText }}</button>
            </slot>
          </div>

        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Modal'
    },
    small: {
      type: Boolean,
      default: false
    },
    large: {
      type: Boolean,
      default: false
    },
    full: {
      type: Boolean,
      default: false
    },
    force: {
      type: Boolean,
      default: false
    },
    confirmText: {
      type: String,
      default: 'OK'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    confirmClass: {
      type: String,
      default: 'btn btn-info'
    },
    closeWhenConfirm: {
      type: Boolean,
      default: false
    },
    showFooter: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      'modal-lg': this.large,
      'modal-sm': this.small,
      'modal-full': this.full
    }
  },
  created () {
    if (this.show) {
      document.body.className += ' modal-open';
    }
  },
  beforeDestroy () {
    document.body.className = document.body.className.replace(/\s?modal-open/, '')
  },
  watch: {
    show(val) {
      if (val) {
        document.body.className += 'modal-open'
      } else {
        if (!this.duration) {
          this.duration = window.getComputedStyle(this.$el)['transition-duration'].replace('s', '') * 1000
        }
        window.setTimeout(() => {
          document.body.className = document.body.className.replace(/\s?modal-open/, '')
        }, this.duration || 0)
      }
    }
  },
  methods: {
    confirm() {
      this.$emit('confirm')
    },
    cancel() {
      this.$emit('cancel')
    },
    clickMask() {
      if (!this.force) {
        this.cancel()
      }
    }
  }
}
</script>


