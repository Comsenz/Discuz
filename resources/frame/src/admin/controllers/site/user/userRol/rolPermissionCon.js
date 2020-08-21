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
      is_subordinate: false,   // 是否开启推广下线
      is_commission: false,   // 是否开启分成
      scale: 0, // 提成比例
      bindPhoneDisabled: false,   // 是否开启短信验证
      wechatPayment: false,       // 是否开启微信支付
      activeTab:  {               //设置权限当前项
        title: '内容发布权限',
        name: 'publish'
      },
      menuData: [                //设置权限
        {
          title: '内容发布权限',
          name: 'publish'
        },
        {
          title: '安全设置',
          name: 'security'
        },
        {
          title: '前台操作权限',
          name: 'operate'
        },
        {
          title: '前台管理权限',
          name: 'manage'
        },
        {
          title: '其他权限',
          name: 'other'
        },
        {
          title: '默认权限',
          name: 'default'
        }
      ]
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
          this.scale = res.data.attributes.scale;
          this.is_subordinate = res.data.attributes.is_subordinate;
          this.is_commission = res.data.attributes.is_commission; 
          data.forEach((item) => {
            this.checked.push(item._data.permission)
          })     
        }
      }).catch(err => {
      })
    },
    patchGroupPermission() {
      let checked = this.checked;
      if(this.is_commission || this.is_subordinate){
        if(checked.indexOf('other.canInviteUserScale')=== -1) {
          checked.push('other.canInviteUserScale');
        }  
      }else {
        checked.forEach((item,index) => {
           if(item==='other.canInviteUserScale') {
             checked.splice(index,1);
           }
        }) 
      }
      this.appFetch({
        url: 'groupPermission',
        method: 'post',
        data: {
          data: {
            "attributes": {
              "groupId": this.$route.query.id,
              "permissions": checked
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
              "is_subordinate" : this.is_subordinate,
              "is_commission" : this.is_commission,
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
       this.is_subordinate = value;
    },
    handlescaleChange(value) {
       this.is_commission = value;
    },
    checkNum(){
      if(!this.scale){
        return true;
      }
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
