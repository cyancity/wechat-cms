<template>
  <textarea name="content" id="content" rows="12" v-text="content" class="form-control"></textarea>
</template>

<script>
export default {
  props: {
    content: {
      type: String,
      default: null
    }
  }
}
</script>

<style>

</style>
