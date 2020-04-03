import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      appId: '',     //APPID：
      secretId: '',  //App Secret Key：
      appID: '',      // 腾讯云验证码场景 id
      captcha: null,           // 腾讯云验证码实例
      captcha_ticket: '',      // 腾讯云验证码返回票据
      captcha_rand_str: '',    // 腾讯云验证码返回随机字符串
    }
  },
  created() {
    var type = this.$route.query.type;
    this.type = type;
    this.tencentCloudCode()
  },
  methods: {
    tencentCloudCode() {
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.appId = res.readdata._data.qcloud.qcloud_captcha_app_id;
          this.secretId = res.readdata._data.qcloud.qcloud_captcha_secret_key;
          this.appID = res.readdata._data.qcloud.qcloud_captcha_app_id;
        }
      })
    },
    Submission() {   //点击提交
      this.captcha = new TencentCaptcha(this.appID, res => {
        if (res.ret === 0) {
          this.captcha_ticket = res.ticket;
          this.captcha_rand_str = res.randstr;
          //验证通过后注册
          this.setting()
        }
      });
      // 显示验证码
      this.captcha.show();
    },
    setting() {
      this.appFetch({
        url: 'settings',
        method: 'post',
        data: {
          "data": [
            {
              "attributes": {
                "key": 'qcloud_captcha',
                "value": 1,
                "tag": "qcloud"
              }
            },
            {
              "attributes": {
                "key": 'qcloud_captcha_app_id',
                "value": this.appId,
                "tag": "qcloud"
              }
            },
            {
              "attributes": {
                "key": 'qcloud_captcha_secret_key',
                "value": this.secretId,
                "tag": "qcloud",
              }
            },
            {
              "attributes": {
                "key": 'qcloud_captcha_ticket',
                "value": this.captcha_ticket,
                "tag": "qcloud",
              }
            },
            {
              "attributes": {
                "key": 'qcloud_captcha_randstr',
                "value": this.captcha_rand_str,
                "tag": "qcloud",
              }
            },
          ]
        }
      }).then(res => {
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$message.error(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$message.error(res.errors[0].code);
          }
        } else {
          this.$message({ message: '提交成功', type: 'success' });
        }
      })
    }


  },
  beforeRouteLeave(to, from, next) {
    // 隐藏验证码
    if (this.captcha) {
      this.captcha.destroy();
    }
    next();
  },

  components: {
    Card,
    CardRow
  }
}
