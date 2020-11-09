import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      tableData: [
        {
          name: '云API',
          type: 'qcloud_close',
          description: '配置云API的密钥后，才可使用腾讯云的各项服务和能力，<a href="https://discuz.chat/manual-admin/2.html#_2-7-1-%E4%BA%91api" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconAPI',
          setFlag: true
        }, {
          name: '图片内容安全',
          type: 'qcloud_cms_image',
          description: '请先配置云API，开通腾讯云图片内容安全服务，并确保有对应套餐包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-2-%E5%9B%BE%E7%89%87%E5%86%85%E5%AE%B9%E5%AE%89%E5%85%A8" target="_blank">查看文档</a>',
          status: '',
          icon: 'icontupian',
          setFlag: false
        }, {
          name: '文本内容安全',
          type: 'qcloud_cms_text',
          description: '请先配置云API，开通腾讯云文本内容安全服务，并确保有对应套餐包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-3-%E6%96%87%E6%9C%AC%E5%86%85%E5%AE%B9%E5%AE%89%E5%85%A8" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconwenben',
          setFlag: false
        }, {
          name: '短信',
          type: 'qcloud_sms',
          description: '请先配置云API，开通腾讯云短信服务，并确保腾讯云账户的短信额度充足，<a href="https://discuz.chat/manual-admin/2.html#_2-7-4-%E7%9F%AD%E4%BF%A1" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconduanxin',
          setFlag: true
        }, {
          name: '实名认证',
          type: 'qcloud_faceid',
          description: '请先配置云API，开通腾讯云的人脸核身服务，并确保有对应资源包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-5-%E5%AE%9E%E5%90%8D%E8%AE%A4%E8%AF%81" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconshimingrenzheng',
          setFlag: false
        }, {
          name: '对象存储',
          type: 'qcloud_cos',
          description: '请先配置云API，开通腾讯云的对象存储服务，并确保有对应资源包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-6-%E5%AF%B9%E8%B1%A1%E5%AD%98%E5%82%A8" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconduixiangcunchu',
          setFlag: true
        }, {
          name: '云点播',
          type: 'qcloud_vod',
          description: '请先配置云API，开通腾讯云的云点播VOD服务，并确保有对应资源包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-7-%E8%A7%86%E9%A2%91" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconshipin',
          setFlag: true
        }, {
          name: '验证码',
          type: 'qcloud_captcha',
          description: '请先配置云API，开通腾讯云的验证码服务，并确保有对应的资源包，<a href="https://discuz.chat/manual-admin/2.html#_2-7-8-%E9%AA%8C%E8%AF%81%E7%A0%81" target="_blank">查看文档</a>',
          status: '',
          icon: 'iconyanzhengma',
          setFlag: true
        }

      ]
    }
  },
  created() {
    this.tencentCloudStatus()
  },
  methods: {
    configClick(type) {
      switch (type) {
        case 'qcloud_close':
          this.$router.push({ path: '/admin/tencent-cloud-config/cloud', query: { type: type } });
          break;
        case 'qcloud_sms':
          this.$router.push({ path: '/admin/tencent-cloud-config/sms', query: { type: type } });
          break;
        case 'qcloud_cos':
          this.$router.push({ path: '/admin/tencent-cloud-config/cos', query: { type: type } });
          break;
        case 'qcloud_vod':
          this.$router.push({ path: '/admin/tencent-cloud-config/vod', query: { type: type } });
          break;
        case 'qcloud_captcha':
          this.$router.push({ path: '/admin/tencent-cloud-config/code', query: { type: type } })
        default:
          this.loginStatus = 'default';
      }
    },
    tencentCloudStatus() {
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          if (res.readdata._data.qcloud.qcloud_close) {
            this.tableData[0].status = true
          } else {
            this.tableData[0].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cms_image) {
            this.tableData[1].status = true
          } else {
            this.tableData[1].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cms_text) {
            this.tableData[2].status = true
          } else {
            this.tableData[2].status = false
          }
          if (res.readdata._data.qcloud.qcloud_sms) {
            this.tableData[3].status = true
          } else {
            this.tableData[3].status = false
          }
          if (res.readdata._data.qcloud.qcloud_faceid) {
            this.tableData[4].status = true
          } else {
            this.tableData[4].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cos) {
            this.tableData[5].status = true
          } else {
            this.tableData[5].status = false
          }
          if (res.readdata._data.qcloud.qcloud_vod) {
            this.tableData[6].status = true
          } else {
            this.tableData[6].status = false
          }
          if (res.readdata._data.qcloud.qcloud_captcha) {
            this.tableData[7].status = true
          } else {
            this.tableData[7].status = false
          }
        }
      })
    },
    loginSetting(index, type, status) {
      if (type == 'qcloud_close') {
        this.changeSettings('qcloud_close', status);
      } else if (type == 'qcloud_cms_image') {
        this.changeSettings('qcloud_cms_image', status);
      } else if (type == 'qcloud_cms_text') {
        this.changeSettings('qcloud_cms_text', status);
      } else if (type == 'qcloud_sms') {
        if (status == 0) {
          this.$confirm('若您在用户角色中设置了发布内容需先绑定手机，关闭短信服务将同时清空该设置。若当前注册模式为手机号模式，将更改为用户名模式。', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            this.changeSettings('qcloud_sms', status);
          })
        } else {
          this.changeSettings('qcloud_sms', status);
        }
      } else if (type == 'qcloud_faceid') {
        if (status == 0) {
          this.$confirm('若您在用户角色中设置了发布内容需先实名认证，关闭实名认证服务将同时清空该设置', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            this.changeSettings('qcloud_faceid', status);
          })
        } else {
          this.changeSettings('qcloud_faceid', status);
        }
      } else if (type == 'qcloud_cos') {
        this.changeSettings('qcloud_cos', status);
      } else if (type == 'qcloud_vod') {
        this.changeSettings('qcloud_vod', status);
      } else if (type == 'qcloud_captcha') {
        this.changeSettings('qcloud_captcha', status);
      }


    },
    changeSettings(typeVal, statusVal) {
      //登录设置状态修改
      this.appFetch({
        url: 'settings',
        method: 'post',
        data: {
          "data": [
            {
              "attributes": {
                "key": typeVal,
                "value": statusVal,
                "tag": 'qcloud'
              }
            }
          ]

        }
      }).then(data => {
        if (data.errors) {
          this.$message.error(data.errors[0].code);
        } else {
          this.$message({
            message: '修改成功',
            type: 'success'
          });
          this.tencentCloudStatus();
        }
      }).catch(error => {
        // cthis.$message.error('修改失败');
      })
    },

  },
  components: {
    Card,
    CardRow
  }
}
