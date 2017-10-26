<template>
  <div class="row">
    <div class="ibox-title">
      <small class="pull-right" style="margin-top: 7px">
        <slot name="buttons"></slot>
      </small>
      <h5>{{ title }}</h5>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default() {
        return null
      }
    }
  }
}
</script>

<style>

</style>
