import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      vodTranscode: '', //转码模板
      vodExt: '',
      vodSize: '',//短信签名

    }
  },
  created() {
    var type = this.$route.query.type;
    this.type = type;
    this.tencentCloudSms()
  },
  methods: {
    tencentCloudSms() {
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.vodTranscode = res.readdata._data.qcloud.qcloud_vod_transcode;
          this.vodExt = res.readdata._data.qcloud.qcloud_vod_ext;
          this.vodSize = res.readdata._data.qcloud.qcloud_vod_size;
        }
      })
    },
    Submission() {
      if (this.vodTranscode === '') {
        this.$message("请填写转码模板");
        return
      }
      this.appFetch({
        url: 'settings',
        method: 'post',
        data: {
          "data": [
            {
              "attributes": {
                "key": 'qcloud_vod_transcode',
                "value": this.vodTranscode,
                "tag": "qcloud"
              }
            },
            {
              "attributes": {
                "key": 'qcloud_vod_ext',
                "value": this.vodExt,
                "tag": "qcloud",
              }
            },
            {
              "attributes": {
                "key": 'qcloud_vod_size',
                "value": this.vodSize,
                "tag": "qcloud",
              }
            }
          ]
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.$message({ message: '提交成功', type: 'success' });
        }
      })
    }

  },
  components: {
    Card,
    CardRow
  }
}
