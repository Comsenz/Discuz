/*
* 角色权限编辑
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      checked: [],
      videoDisabled: false,       // 是否开启云点播
      captchaDisabled: false,     // 是否开启验证码
      realNameDisabled: false,    // 是否开启实名认证
      showScale: false,   // 是否开启推广下线
      scale: 0, // 提成比例
      bindPhoneDisabled: false,   // 是否开启短信验证
      wechatPayment: false,       // 是否开启微信支付
    }
  },
  methods: {
    signUpSet() {
      this.appFetch({
        url: 'forum',
        method: 'get',
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          if (res.readdata._data.qcloud.qcloud_vod === false) {
            this.videoDisabled = true
          }
          if (res.readdata._data.qcloud.qcloud_captcha === false) {
            this.captchaDisabled = true
          }
          if (res.readdata._data.qcloud.qcloud_faceid === false) {
            this.realNameDisabled = true
          }
          if (res.readdata._data.qcloud.qcloud_sms === false) {
            this.bindPhoneDisabled = true
          }
          if (res.readdata._data.paycenter.wxpay_close === false) {
             this.wechatPayment = true;
          }
        }
      })
    },
    /*
    * 权限列表中英文对应拿到后，在页面的label中对应填写
    * */
    

    submitClick() {
      if(!this.checkNum()){
        return;
      }
      this.patchGroupScale();
      this.patchGroupPermission();
    },

    /*
    * 接口请求
    * */
    getGroupResource() {
      this.appFetch({
        url: "groups",
        method: 'get',
        splice: '/' + this.$route.query.id,
        data: {
          include: ['permission']
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          let data = res.readdata.permission;
          this.checked = [];
          data.forEach((item) => {
            this.checked.push(item._data.permission)
          })
          this.scale = res.data.attributes.scale;
          if(this.checked.indexOf('other.canInviteUserScale')!=-1) {
            this.showScale = true;
          }
          
        }

      }).catch(err => {
      })
    },
    patchGroupPermission() {
      this.appFetch({
        url: 'groupPermission',
        method: 'post',
        data: {
          data: {
            "attributes": {
              "groupId": this.$route.query.id,
              "permissions": this.checked
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.$message({
            showClose: true,
            message: '提交成功',
            type: 'success'
          });
        }
      }).catch(err => {
      })
    },

    patchGroupScale() {
      this.appFetch({
        url: 'groups',
        method: 'PATCH',
        splice: '/' + this.$route.query.id,
        data: {
          data: {
            "attributes": {
              'name':this.$route.query.name,
              "scale": this.scale,
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        }
      }).catch(err => {
      })
    },

    handlePromotionChange(value){
      this.showScale = value;
    },

    checkNum(){
      const reg = /^([0-9](\.\d)?|10)$/;
      if(!reg.test(this.scale)){
        this.$message({
          message: "提成比例必须是0~10的整数或者一位小数",
          type: "error"
        });
        return false;
      }
      return true;
    }
   
  },
  created() {
    this.getGroupResource();
    this.signUpSet()
  },
  components: {
    Card,
    CardRow
  }
}
