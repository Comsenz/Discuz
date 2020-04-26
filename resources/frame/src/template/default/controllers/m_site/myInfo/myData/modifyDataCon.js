/**
 * 修改资料
 */
import ModifyHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';
import appCommonH from '../../../../../../helpers/commonHelper';
import { correctImg } from '../../../../../../common/utils/correctImg';

export default {
  data: function () {
    return {
      headPortrait: '',      //头像
      modifyPhone: '',       //修改手机号
      changePwd: '',         //修改密码
      bindType: '',          //绑定类型
      wechatId: '',          //id
      wechatNickname: '',
      tipWx: '',
      isWeixin: '',
      isPhone: '',
      realName: '',          //实名证明
      identity: '',          //身份证号码
      canWalletPay: '',      //钱包密码
      realNameShow: 'true',      //实名认证是否显示
      openid: '',       //微信openid
      myModifyPhone: '',
      isReal: false,     //是否实名认证
      updataLoading: false,  //上传状态
    }
  },

  components: {
    ModifyHeader
  },
  created() {
    this.modifyData() //修改资料
    this.wechat()
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    if (this.isWeixin) {
      this.tipWx = '确认解绑微信及退出登录';
    } else {
      this.tipWx = '确认解绑微信'
    }
    let qcloud_faceid = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_faceid;
    if (qcloud_faceid == false) {
      this.realNameShow = false
    }
    let qcloud_sms = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_sms;
    if (qcloud_sms == false) {
      this.myModifyPhone = false
    } else {
      this.myModifyPhone = true
    }
  },
  methods: {
    myModify(str) {
      switch (str) {
        case 'modify-phone':
          this.$router.push('/modify-phone'); //修改手机号
          break;
        case 'change-pwd':
          this.$router.push('/change-pwd'); //修改密码
          break;
        case 'bind-new-phone':
          this.$router.push('/bind-new-phone'); //绑定新手机号
          break;
        case 'bind-new-phone':
          this.$router.push('/real-name'); //实名认证
          break;
        case 'change-pay-pwd':
          //设置钱包密码','跳转设置钱包密码页面
          if (this.canWalletPay) {
            this.$router.push({ path: 'verify-pay-pwd', query: { modifyPhone: this.modifyPhone } });
          } else {
            this.$router.push({ path: 'setup-pay-pwd' });
          }
          break;
        default:
          this.$router.push('/');
      }
    },

    modifyData() {
      let userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {
          include: 'wechat'
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.isReal = res.readdata._data.isReal;              //实名认证
          this.modifyPhone = res.readdata._data.mobile;         //用户手机号
          this.headPortrait = res.readdata._data.avatarUrl;     //用户头像
          this.wechatId = res.readdata._data.id;                //用户Id
          this.canWalletPay = res.readdata._data.canWalletPay;  //钱包密码
          if (res.readdata.wechat) {
            this.wechatNickname = res.readdata.wechat._data.nickname //微信昵称
          } else {
            this.wechatNickname = false
          }
          // if(res.readdata)
          if (res.readdata._data.realname !== '') {
            this.realName = `${res.readdata._data.realname}  ${res.readdata._data.identity}`
          } else {
            this.realName = false
          }
          // this.modifyData()
        }
      })


    },
    handleFile: async function (e) {  //上传头像

      let file = e.target.files[0];
      this.updataLoading = true;

      const newfile = await correctImg(file);
      file = newfile || file;
      // 获取file
      // 实例化
      let formdata = new FormData()
      formdata.append('avatar', file)
      let userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'upload',
        method: 'post',
        splice: userId + '/avatar',
        data: formdata
      }).then(res => {
        this.updataLoading = false;
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          // alert(res)
          this.$toast('上传头像成功!');
          this.headPortrait = res.data.attributes.avatarUrl;
          this.modifyData()
        }
      })
    },

    myModifyWechat() {
      this.$dialog.confirm({
        title: this.tipWx,
        // message: '弹窗内容'
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.wechat(this.wechatId)
        }
      })
      // .catch(() => {
      //   // on cancel
      // });
    },
    wechat(id) {   //解绑微信
      if (id != '' && id != null) {
        this.appFetch({
          url: 'wechatDelete',
          method: 'delete',
          splice: this.wechatId + '/' + 'wechat',
          data: {
          }
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
          } else {
            let isWeixin = this.appCommonH.isWeixin().isWeixin;
            if (isWeixin) {
              // var userId = browserDb.getLItem('tokenId');
              localStorage.clear();
              this.$router.push({ path: '/wx-sign-up-bd' })
            } else {
              this.modifyData()
            }
          }
        })
      }
    },
    wechatBind() {    //去绑定微信
      if (this.isWeixin) {
        window.location.href = '/api/oauth/wechat';
        localStorage.clear();
        /*this.appFetch({
          url: 'wechatBind',
          method: 'get',
          data: {}
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
          } else {
            window.location.href = res.readdata._data.location
          }
        })*/
      } else if (this.isPhone) {
        this.$toast.fail('请在微信客户端中进行绑定操作');
        // this.appFetch({
        //   url:'wechatBind',
        //   method:'get',
        //   data:{}
        // }).then(res=>{
        //   if (res.errors){
        //     this.$toast.fail(res.errors[0].code);
        //   }else{
        //   window.location.href = res.readdata._data.location
        //   }
        // })
      } else {
        this.$toast.fail('请在微信客户端中进行绑定操作');

        // window.location.href = '/api/oauth/wechat/pc';

        // this.$router.push({path:'wx-qr-code'});
        // browserDb.setSItem('beforeVisiting','modify-data');

        /*this.appFetch({     //pc端绑定
          url: 'wxPcLogin',
          method: 'get',
          data: {}
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
          } else {
            window.location.href = res.readdata._data.location
          }
        })*/
      }

    },

  }
}
