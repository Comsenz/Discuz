/**
 * 修改资料
 */


import ModifyHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';


export default {
  data:function () {
    return {
      headPortrait:'',      //头像
      modifyPhone:'',       //修改手机号
      changePwd:'',         //修改密码
      bindType:'',          //绑定类型
      wechatId:'',          //id
      wechatNickname:'',
      tipWx:'',
      isWeixin:'',
      realName:'',          //实名证明
      identity:'',          //身份证号码
      canWalletPay:'',      //钱包密码
      realNameShow:'true',      //实名认证是否显示
    }
  },

  components:{
    ModifyHeader
  },
  created(){
    this.modifyData() //修改资料
    this.wechat()
    this.isWeixin =this.appCommonH.isWeixin().isWeixin
    if(this.isWeixin){
      this.tipWx = '确认解绑微信及退出登录'
    }else{
      this.tipWx = '确认解绑微信'
    }
    let qcloud_faceid = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_faceid;
    if(qcloud_faceid == false){
      this.realNameShow = false
    }
  },
  methods:{
    myModify(str){
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
          console.log('设置钱包密码','跳转设置钱包密码页面');
          if (this.canWalletPay){
            this.$router.push({path:'verify-pay-pwd'});
          } else {
            this.$router.push({path:'setup-pay-pwd'});
          }
          break;
        default:
          this.$router.push('/');
      }
    },

    modifyData(){
      // this.appFetch({
      //   url:'forum',
      //   method:'get',
      //   data:{

      //   }
      // }).then(res=>{
      //   if (res.errors){
      //     this.$message.error(res.errors[0].code);
      //   }else {
      //     console.log(res,'密码密码')
      //     if(res.readdata._data.qcloud.qcloud_facdid == false){
      //       this.realNameShow = false
      //     }
      //   }
      // })

      let userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+userId,
        data:{
          include:'wechat'
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        }else{
        console.log(res,'实名认证字段')
        console.log(res.readdata._data.realName)
        this.modifyPhone = res.readdata._data.mobile;         //用户手机号
        this.headPortrait = res.readdata._data.avatarUrl;     //用户头像
        this.wechatId = res.readdata._data.id;                //用户Id
        this.canWalletPay = res.readdata._data.canWalletPay;  //钱包密码
        if(res.readdata.wechat){
          console.log(res.readdata.wechat,'999999')
          this.wechatNickname = res.readdata.wechat._data.nickname //微信昵称
        }else{
          this.wechatNickname = false
        }
        // if(res.readdata)
        if(res.readdata.realName !== ''){
          this.realName = `${res.readdata._data.realname}  ${res.readdata._data.identity}`
        }else{
          this.realName = false
        }
        // this.modifyData()
      }
      })


    },
      handleFile: function (e) {
        let file = e.target.files[0];
        console.log(file);

        // 获取file
      // 实例化
        let formdata = new FormData()
        formdata.append('avatar', file)
        console.log(formdata)



      let userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'upload',
        method:'post',
        splice:userId+'/avatar',
        data:formdata
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        }else{
        this.headPortrait = res.data.attributes.avatarUrl;
         this.modifyData()
        }
      })
      },

      myModifyWechat(){
        this.$dialog.confirm({
          title: this.tipWx,
          // message: '弹窗内容'
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
          }else{
          console.log('000000')
          this.wechat(this.wechatId)
          }
        })
        // .catch(() => {
        //   // on cancel
        // });
      },
      wechat(id){   //解绑微信
        if(id!= '' && id!= null){
          this.appFetch({
            url:'wechatDelete',
            method:'delete',
            splice:this.wechatId+'/'+'wechat',
            data:{
            }
          }).then(res=>{
            if (res.errors){
              this.$toast.fail(res.errors[0].code);
            }else{
              let isWeixin =this.appCommonH.isWeixin().isWeixin;
              if(isWeixin){
                // var userId = browserDb.getLItem('tokenId');
                localStorage.clear();
                this.$router.push({path:'/wx-login-bd'})
              }else{
                this.modifyData()
              }
            }
          })
        }    
      },
      wechatBind(){    //去绑定微信
        this.appFetch({
          url:'wechatBind',
          method:'get',
          data:{}
        }).then(res=>{
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
          }else{
          console.log(res.readdata._data.location)
          window.location.href = res.readdata._data.location
          }
        })
      },
     
  }
}
