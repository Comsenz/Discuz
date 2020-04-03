<template>
  <div id="question">
   <div class="content">
      <div class="main">
        <a href="javascript:;"
           class="add-video"
           @click="choseVideo">
          <input type="file"
                 accept="video/mp4"
                 @change="changeVideo()"
                 ref="vcVideo">
          <div></div>
          <!-- <span>{{vcVideoName ? '点此重新上传视频' : '点击上传视频'}}</span> -->
          <span>{{vcVideoName ? vcVideoName : ''}}</span>
        </a>
        <div v-for="uploaderInfo in uploaderInfos"
             :key="uploaderInfo">
          <div v-if="uploaderInfo"
               class="upload-info">
            <div>上传进度：{{Math.floor(uploaderInfo.progress * 100) + '%'}}</div>
            <div>上传结果：{{isUploadSuccess ? '上传成功' : '上传中'}}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="ques2-footer">
      <button :disabled="vcVideoName && disabledBtn === false ? false : true"
              @click="vcAddUpload">
        提交</button>
    </div>
  </div>
</template>

<script>
import TcVod from 'vod-js-sdk-v6'
function getSignature () {
  return API.getTencentVideoSign().then(res => {
    if (res.data.code === 200) {
      return res.data.data.sign
    }
  })
}
export default {
  name: 'Question2',
  components: {
    // Confirm
  },
  data () {
    return {
      isUploadSuccess: false,
      uploaderInfos: [],
      vcVideoName: '',
      surplusTime: '',
      disabledBtn: false,
      sign: ''
    }
  },
  created () {
    // 初始化签名
    this.tcVod = new TcVod({
      getSignature: getSignature
    })
  },
  methods: {
    choseVideo () {
      this.$refs.vcVideo.click()
    },
    changeVideo () {
      this.vcVideoName = this.$refs.vcVideo.files[0].name
      let fileSize = this.$refs.vcVideo.files[0].size
      // 视频大于300M置空
      if (fileSize / 1024 / 1024 > 300) {
      	alert('请上传小于300M的视频')
        this.$refs.vcVideo.files[0] = null
        return
      }
    },
    // 提交视频
    vcAddUpload () {
      var self = this
      var mediaFile = this.$refs.vcVideo.files[0]
      var uploader = this.tcVod.upload({
        // 媒体文件（视频或音频或图片），类型为 File
        // signature: this.sign,
        mediaFile: mediaFile
      })
      // 视频上传进度
      uploader.on('media_progress', function (info) {
        uploaderInfo.progress = info.percent
      })
      // 视频上传完成时
      uploader.on('media_upload', function (info) {
        uploaderInfo.isVideoUploadSuccess = true
      })

      var uploaderInfo = {
        videoInfo: uploader.videoInfo,
        isVideoUploadSuccess: false,
        isVideoUploadCancel: false,
        progress: 0,
        fileId: '',
        videoUrl: '',
        cancel: function () {
          uploaderInfo.isVideoUploadCancel = true
          uploader.cancel()
        },
      }

      this.uploaderInfos.push(uploaderInfo)
      // 腾讯云上传完成给后端
      uploader.done().then(function (doneResult) {
        uploaderInfo.fileId = doneResult.fileId
        uploaderInfo.videoUrl = doneResult.video.url
        API.saveSubAnswer({ questions_id: self.state.matchInfo.sub[self.questionIndex].id, url: uploaderInfo.videoUrl }).then(res => {
          if (res.data.code === 200) {
            self.isUploadSuccess = true
            self.$router.replace({
              name: 'Congratulate'
            })
          } else {
            if (self.timeEnd) {
              let configObj = {
                titleText: '抱歉，上传失败，答题结束。', // 提示框标题
                content: '您可在5个工作日内，将视频发送至haixuan@dxy.cn。若超过5个工作日，则视为放弃。', // 提示框的内容
                confirmText: '确定', // 确认按钮的文字
                type: 'alert', // 表明
                data: 'goCongratulate'
              }
              self.$refs.myConfirm.show(configObj)
            } else {
              let configObj = {
                titleText: '上传失败', // 提示框标题
                content: '对不起，上传失败，请重新上传。', // 提示框的内容
                confirmText: '确定', // 确认按钮的文字
                type: 'alert' // 表明
              }
              self.$refs.myConfirm.show(configObj)
            }
          }
          // self.disabledBtn = false;
        }).catch(response => {
          let configObj = {
            titleText: '网络出错，点击确定帮你重新保存结果', // 提示框标题
            confirmText: '确定', // 确认按钮的文字
            type: 'alert' // 表明只有那个clickConfirm触发
          }
          self.$refs.myConfirm.show(configObj)
        })
      }).then(function (videoUrl) {
        self.vcVideoName = ''
      })
    },
    getSignature () {
      API.getTencentVideoSign().then(res => {
        if (res.data.code === 200) {
          return res.data.data.sign
        }
      })
    }
  }
}
</script>
