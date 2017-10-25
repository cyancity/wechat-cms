<template>
  <div :class="wrapper">
    <div class="cropperWrapper">
      <img :src="image.url" ref="cropImage" style="width: 100%">
    </div>
    <span class="footer" slot="footer">
      <button class="btn btn-outline-secondary" @click="cancel">
        {{ $t('form.cancel') }}
      </button>
      <button class="btn btn-primary" @click="upload">
        {{ $t('form.ok') }}
      </button>
    </span>
  </div>
</template>

<script>
import 'cropperjs/dist/cropper.css'
import Cropper from 'cropperjs'

export default {
  components: {
    Cropper
  },
  props: {
    image: {
      type: Object,
      default() {
        return {}
      }
    },
    wrapper: {
      type: String,
      default() {
        return ''
      }
    },
    cropperWrapper: {
      type: String,
      default() {
        return ''
      }
    }
  },
  data() {
    return {
      cropper: null
    }
  },
  mounted () {
    this.createCropper()
  },
  watch: {
    image(val) {
      this.replaceUrl()
    }
  },
  methods: {
    replaceUrl() {
      this.cropper.replace(this.image.url)
    }
  },
  createCropper() {
     var image = this.$ref.cropImage
     this.cropper = new Cropper(image, {
       aspectRatio: 1,
       autoCropArea: 1,
       movable: false,
       zoomable: false
     })
  },
  upload(e) {
    let formData = {
      'image': this.image,
      'data': this.cropper.getData()
    }

    this.$axios.post('crop/avatar', formData)
      .then(res => {
        this.$emit('succeed')
      })
  },
  cancel() {
    this.$emit('canceled')
  }
}
</script>

<style lang="stylus"  scoped>
  .footer
    margin-top 30px
    display block
    text-align center
    background-color transparent

    button
    margin-left 40px
    margin-right 40px
</style>
