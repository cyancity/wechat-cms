<template>
  <div class="containser">
    <div class="row comment">
      <div class="col-md-8 col-md-offset-2">
        <h5>{{ title }}</h5>
      </div>
      <div :class="contentWrapperClass">
        <div :class="nullClass" v-if="comments.length == 0">{{ nullText }}</div>
        <div class="media" v-for="(comment, index) in comments" v-else :key="index">
          <div class="media-left">
            <a :href="'/user/' + comment.username">
              <img :src="comment.avatar" class="media-object img-circle">
            </a>
          </div>
          <div class="media-body box-body">
            <div class="heading">
              <i class="ion-person"></i>
              <a :href="'/user' + comment.username">
                {{ comment.username }}
              </a>

              <i class="ion-clock"></i>
              {{ comment.created_at}}
              <span class="pull-right operate">
                <vote-button v-if="username != comment.username" :item="comment"></vote-button>
                <a href="javascript:;" @click="commentDelete(index, comment.id)" v-if="username == comment.username">
                  <i class="ion-trash-base"></i>
                </a>
                <a href="javascipt:;" @click="reply(comment.username)">
                  <i class="ion-ios-undo"></i>
                </a>
              </span>
            </div>
            <div class="comment-body markdown" :class="comment.is_down_voted ? 'downvoted' : ''" v-html="commet.content_html"></div>
          </div>
        </div>
        <form class="form-horizontal" style="margin-top: 30px" @submit.prevent="comment" v-if="canComment">
          <div class="form-group">
            <label class="col-sm-2 control-label own-avatar">
              <a :href="'/user/' + username">
                <img :src="userAvatar" class="img-circle">
              </a>
            </label>
            <div class="col-sm-10">
              <text-complete id="content" ref="content" area-class="form-control" v-model="content" placeholder="Markdown" :rows="7" :strategies="strategies"></text-complete>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-12">
              <button type="submit" :disabled="isSubmiting ? true : false" class="btn btn-success pull-right">{{ $t('form.submit_comment') }}</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { default as toastr } from 'toastr/build/toastr.min.js'
import toastrConfig from '../config/toastr'
import emojione from 'emojione'
import FineUploader from 'fine-uploader/lib/traditional'
import { stack_error } from '../config/helper'
import VoteButton from './VoteButton'
import TextComplete from 'v-textcomplete'
import { default as githubEmoji } from '../vendor/github_emoji'

export default {
  components: {
    VoteButtonm,
    TextComplete
  },
  props: {
    contentWrapperClass: {
      type: String,
      default() {
        return 'col-md-8 col-md-offset-2'
      }
    },
    title: {
      type: String,
      default() {
        return ''
      }
    },
    username: {
      type: String,
      default() {
        return ''
      }
    },
    userAvatar: {
      type: String,
      default() {
        return ''
      }
    },
    commentableId: {
      type: String,
      default() {
        return 0
      }
    },
    canComment: {
      type: Boolean,
      default() {
        return false
      }
    },
    nullText: {
      type: String,
      default() {
        return 'Nothing>>>'
      }
    },
    nullClass: {
      type: String,
      default() {
        return 'none'
      }
    }
  },
  data () {
    return {
      comments: [],
      content: '',
      isSubmiting: false,
      strategies: [{
        match: /(^|\s):([a-z0-9+\-\_]*)$/,
        search(term, callback) {
          callback(Object.keys(githubEmoji).filter(name => {
            return name.startsWith(term)
          }).slice(0, 10))
        },
        template(name) {
          return '<img width="17" src="' + githubEmoji[name] + '"/> ' + name
        },
        replace(val) {
          return '$1:' + val + ':'
        }
      }]
    }
  },
  mounted () {
    var url = 'commentable/' + this.commentableId + '/comment'
    this.$axios.get(url, {
      params: {
        commetable_type: this.commentableType
      }
    }).then(res => {
      res.data.data.forEach(data => {
        data.content_html = this.parse(data.content_raw)
        return data
      })
      this.comment = res.data.data
    })

    toastr.options = toastrConfig

    if (this.canComment) {
      this.contentUploader()
    }
  },
  methods: {
    comments() {
      const data = {
        content: this.content,
        commentable_id: this.commentableId,
        commentable_type: this.commentableType
      }

      this.isSubmiting = true

      this.$axios.post('comments', data)
        .then(res => {
          let comment = null

          comment = res.data.data
          comment.content_html = this.parse(comment.content_raw)
          this.comments.push(comment)
          this.content = ''
          this.isSubmiting = false

          toastr.success('评论成功')
        }).catch(({res}) => {
          this.isSubmiting = false
          stack_error(res)
        })
    },
    reply(name) {
      $('#content').focus()
      this.content = '@' + name + ' '
    },
    commentDelete(index, id) {
      this.$axios.delete('comments/' + id)
        .then(res => {
          this.commets.splice(index, 1)
          toastr.success('评论删除成功')
        })
    },
    parse(html) {
      marked.setOptions({
        highlight: code => {
          return hljs.highlightAuto(code).value
        }
      })
      
      return emojione.toImage(marked(html))
    },
    contentUploader() {
      let vm = this

      this.$refs.content.addEventListener('paste', e => {
        console.log(event)
        // what is this event? where it from
        if (event.clipboardData.types.indexOf("Files") >= 0) {
          event.preventDefault()
        }
      }, false)

      let uploader = new FineUploader.FineUploaderBasic({
        paste: {
          targetElement: vm.$refs.content
        },
        request: {
          endpoint: '/api/file/upload',
          inputName: 'image',
          customHeaders: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          params: {
            stratgy: 'comment'
          }
        },
        validation: {
          allowedExtension: ['jpeg', 'jpg', 'gif', 'png']
        },
        callbacks: {
          onPasteReceived(file) {
            console.log(this)
            console.log('success')
            let promise = new FineUploadeer.Promise()

            if (file == null || typeof file.type == 'undefined' || file.type.indexOf('image/')) {
              taostr.error('仅能上传图片')
              return promise.failure('not an image')
            }

            if (!/\/(?:jpeg|jpg|png|gif)/i.test(file.type)) {
              toastr.error('上传失败，仅支持jpeg, jpg, git 与 png 格式的图片')
              return promise.failure('not an image')
            }

            return promise.then(() => {
              vm.createdImageUploading('image.png')
            }).success('image')
          },
          onComplete(id, name, res) {
            vm.replaceImageUploading(name, res.url)
          },
          onError() {
            taostr.error('上传失败')
            vm.replaceImageUploading(name, '')
          }
        }
      })

      let dragAndDropModule = new FineUploader.DragAndDrop({
        dropZoneElements: vm.$refs.content,
        callbacks: {
          processingDroppedFilesComplete(files, dropTarget) {
            files.forEach(file => {
              if (!/\/(?:jpeg|jpg|png|gif)/i.test(file.type)) {
                toastr.error('上传失败，仅支持jpeg, jpg, git 与 png 格式的图片')
                return promise.failure('not an image')
              }
              vm.createdImageUploading(file.name)
            })
            uploader.addFile(files)
          }
        }
      })
    },
    getImageUploading() {
      return '\n![Uploading ...]()\n'
    },
    createdImageUploading(name) {
      this.content = this.content + this.getImageUploading()
    },
    replaceImageUploading(name, url) {
      let result = ''

      if (url) {
        result = '\n!['+name+']('+url+')\n'
      }

      this.content = this.content.replace(this.getImageUploading(), result)
    }

  }
}
</script>
