
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      loginStatus: 'default',   //default h5 applets pc
      appId: '',
      appSecret: '',
      type: '',
      prefix: '',
      closeVideo: false,
      typeCopywriting: {
        wx_offiaccount: {
          title: '公众号配置',
          appIdDescription: '填写申请公众号后，你获得的APPID ',
          appSecretDescription: '填写申请公众号后，你获得的App secret',
          serverUrl:'服务器地址URL',
          appToken:'填写长度为3-32字符，必须为英文或数字的字符。或',
          encodingAESKey:'消息加密密钥由43位字符组成，可随机修改，字符范围为A-Z，a-z，0-9。或',
          url: 'https://mp.weixin.qq.com/',
        },
        wx_miniprogram: {
          title: '小程序配置',
          appIdDescription: '填写申请小程序后，你获得的APPID ',
          appSecretDescription: '填写申请小程序后，你获得的App secret',
          closeVideo: '开启后，在小程序前台将展示视频内容，并且可进行视频内容的发布。开启前，请务必确保您的小程序已有相应的视频播放类目的权限。具体类目权限请 点此查看',
          url: 'https://developers.weixin.qq.com/miniprogram/product/material/',
        },
        wx_oplatform: {
          title: 'PC端微信扫码登录',
          appIdDescription: '填写申请PC端微信扫码后，你获得的APPID ',
          appSecretDescription: '填写申请PC端微信扫码后，你获得的App secret',
          url: 'https://open.weixin.qq.com/',
        }
      },
      serverUrl:'',             //服务器URL
      appToken:'',              //令牌
      encodingAESKey:'',        //消息加解密密匙
    }
  },
  created() {
    var type = this.$route.query.type;
    this.type = type;
    this.loadStatus();
  },
  methods: {
    loadStatus() {
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {}
      }).then(data => {
        if (data.errors) {
          this.$message.error(data.errors[0].code);
        } else {
          // 获取对应值渲染
          this.getPrefix(this.type, data);
        }
      }).catch(error => {
      })
    },
    submitConfiguration() {
      let data = [];

      data = [
        {
          "attributes": {
            "key": this.prefix + "app_id",
            "value": this.appId,
            "tag": this.type
          }
        },
        {
          "attributes": {
            "key": this.prefix + "app_secret",
            "value": this.appSecret,
            "tag": this.type
          }
        },
        {
          "attributes": {
            "key": this.prefix + "video",
            "value": this.closeVideo,
            "tag": 'wx_miniprogram'
          }
        },

      ];

      if (this.type === 'wx_offiaccount'){
        data.push(
          {
            "attributes": {
              "key": "oplatform_url",
              "value": this.serverUrl,
              "tag": 'wx_oplatform'
            }
          },
          {
            "attributes": {
              "key": "oplatform_app_token",
              "value": this.appToken,
              "tag": 'wx_oplatform'
            }
          },
          {
            "attributes": {
              "key": "oplatform_app_aes_key",
              "value": this.encodingAESKey,
              "tag": 'wx_oplatform'
            }
          }
        )
      }

      this.appFetch({
        url: 'settings',
        method: 'post',
        data: {
          "data": data
        }
      }).then(data => {
        if (data.errors) {
          this.$message.error(data.errors[0].code);
        } else {
          // this.$router.push({
          //   path: '/admin/worth-mentioning-set'
          // });
          this.$message({
            message: '提交成功',
            type: 'success'
          });
        }
      })
    },
    getPrefix(type, data) {    // 传参
      switch (type) {
        case 'wx_offiaccount':
          this.prefix = 'offiaccount_';
          this.appId = data.readdata._data.passport.offiaccount_app_id;
          this.appSecret = data.readdata._data.passport.offiaccount_app_secret;
          this.serverUrl = data.readdata._data.passport.oplatform_url;
          this.appToken = data.readdata._data.passport.oplatform_app_token;
          this.encodingAESKey = data.readdata._data.passport.oplatform_app_aes_key;
          break;
        case 'wx_miniprogram':
          this.prefix = 'miniprogram_';
          this.appId = data.readdata._data.passport.miniprogram_app_id;
          this.appSecret = data.readdata._data.passport.miniprogram_app_secret;
          this.closeVideo = data.readdata._data.set_site.miniprogram_video;
          break;
        case 'wx_oplatform':
          this.prefix = 'oplatform_';
          this.appId = data.readdata._data.passport.oplatform_app_id;
          this.appSecret = data.readdata._data.passport.oplatform_app_secret;
          break;
      }
    },
    randomClick(type){
      if (type === 'token'){
        this.appToken = Math.random(Date.parse(new Date())).toString(35).substr(2);
      } else if (type === 'aes'){
        let aeskey = '';

        for (let i = 0; i<5 ; i++){
          aeskey += Math.random(Date.parse(new Date())).toString(35).substr(2);
        }

        this.encodingAESKey = aeskey.slice(0, 43)
      }
    },
  },
  components: {
    Card,
    CardRow
  }
}
