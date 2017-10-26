<template>
  <div>
    <span :style="{color: color}" @click="setStatus(rowData)">
      <i class="ion-record"></i>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    rowData: {
      type: Object,
      required: true
    },
    apiUrl: {
      type: String,
      required: true
    }
  },
  computed: {
    color() {
      return this.rowData.status ? '#8eb4cb' : '#bf5329'
    }
  },
  methods: {
    setStatus(rowData) {
      swal({
        title: '更改记录状态',
        text: 'The action may affect some data, Please think twice',
        type: 'warning',
        showCancelButton: type,
        closeOnConfirm: true,
        confirmButtonColor: '#DD6855',
        confirmButtonText: 'Yes, changed it!'
      }),
      () => index.postData(rowData)
    },
    postData(rowData) {
      this.$axios.post(this.apiUrl + '/' + rowData.id + 'status', {status: !rowData.status})
        .then(res => {
          this.rowData.status = !this.rowData.status
          if (this.rowData.status) {
            toastr.success('You changed a record of the status success!')
          } else {
            toastr.warning('You changed a record of the status, Please check again')
          }
        }).catch(res => {
          if (res.data.error) {
            toastr.error(res.data.error.message)
          } else {
            toastr.error(res.status + ': Resource' + res.statusText)
          }
        })
    }
  }
}
</script>

<style lang="stylus" scoped>
  span
    cursor pointer
</style>
