import Card from "../../../../view/site/common/card/card";
import CardRow from "../../../../view/site/common/card/cardRow";

export default {
  data: function() {
    return {
      vodTranscode: "", // 转码模板
      vodWatermark: "", // 水印模板ID
      vodExt: "",
      vodSize: "", // 短信签名
      subApplication: "", // 子应用
      screenshot: "", // 截图模版
      vodTaskflowGif: "", // 动图封面任务流名称
      vodUrlKey: "", // 云点播防盗链
      vodUrlExpire: "" // 云点播防盗链签名有效期
    };
  },
  created() {
    var type = this.$route.query.type;
    this.type = type;
    this.tencentCloudSms();
  },
  methods: {
    tencentCloudSms() {
      this.appFetch({
        url: "forum",
        method: "get",
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.vodTranscode = res.readdata._data.qcloud.qcloud_vod_transcode;
          this.vodWatermark = res.readdata._data.qcloud.qcloud_vod_watermark;
          this.vodExt = res.readdata._data.qcloud.qcloud_vod_ext;
          this.vodSize = res.readdata._data.qcloud.qcloud_vod_size;
          this.subApplication = res.readdata._data.qcloud.qcloud_vod_sub_app_id;
          this.screenshot = res.readdata._data.qcloud.qcloud_vod_cover_template;
          this.vodTaskflowGif = res.readdata._data.qcloud.qcloud_vod_taskflow_gif;
          this.vodUrlKey = res.readdata._data.qcloud.qcloud_vod_url_key;
          this.vodUrlExpire = res.readdata._data.qcloud.qcloud_vod_url_expire;
        }
      });
    },
    Submission() {
      if (this.vodTranscode === "") {
        this.$message("请填写转码模板");
        return;
      }
      this.appFetch({
        url: "settings",
        method: "post",
        data: {
          data: [
            {
              attributes: {
                key: "qcloud_vod_sub_app_id",
                value: this.subApplication,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_transcode",
                value: this.vodTranscode,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_watermark",
                value: this.vodWatermark,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_cover_template",
                value: this.screenshot,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_ext",
                value: this.vodExt,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_size",
                value: this.vodSize,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_taskflow_gif",
                value: this.vodTaskflowGif,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_url_key",
                value: this.vodUrlKey,
                tag: "qcloud"
              }
            },
            {
              attributes: {
                key: "qcloud_vod_url_expire",
                value: this.vodUrlExpire,
                tag: "qcloud"
              }
            }
          ]
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.$message({ message: "提交成功", type: "success" });
          this.tencentCloudSms(); //提交成功后获取新数据
        }
      });
    }
  },
  components: {
    Card,
    CardRow
  }
};
