/**
 * 登录-手机号登录
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import {mapState} from 'vuex';
import browserDb from "../../../../../helpers/webDbHelper";

export default {
  data:function () {
    return {
      phone:"",
      sms:"",
      btnContent:'发送验证码',   //发送验证码文本
      time:1,                //发送验证码间隔时间
      disabled:false,        //按钮状态
      isGray: false,
      wxLoginShow: true,
      phoneStatus:'',
      isOne: false,
      siteMode:'',
      btnLoading:false
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  computed:mapState({
    status:state => state.appSiteModule.status,
  }),

  methods:{
    loginUserClick(){
      this.$router.push({path:'/login-user'})
    },
    wxLoginClick(){
      // this.$router.push({path:'/wx-login-bd'})
      this.$router.push({path:'wx-qr-code'});
    },

    getCode(){

      this.appFetch({
        url:'sendSms',
        method:'post',
        data:{
          "data":{
            "attributes":{
              "mobile": this.phone,
              "type": "login"
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0]);
        } else {
          this.$toast.success('发送成功');
        }
      }).catch(err=>{
      });

      this.time = 60;
      this.timer();
    },
    timer(){
      if(this.time>1){
        this.time--;
        this.btnContent = this.time+"s后重新获取";
        this.disabled = true;
        var timer = setTimeout(this.timer,1000);
        this.isGray = true;
      }else if(this.time == 1){
        this.btnContent = "获取验证码";
        clearTimeout(timer);
        this.disabled = false;
        this.isGray = false;
      }
    },

    phoneLoginClick(){
      this.btnLoading = true;
      this.appFetch({
        url:'smsVerify',
        method:'post',
        data:{
          "data":{
            "attributes":{
              "mobile":this.phone ,
              "code": this.sms,
              "type": "login"
            }
          }
        }
      }).then(res=>{
        this.btnLoading = false;
        if (res.errors){
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;
          browserDb.setLItem('Authorization', token);
          browserDb.setLItem('tokenId', tokenId);
          browserDb.setLItem('refreshToken',refreshToken);

          this.getUsers(tokenId).then(res=>{
            if (res.readdata._data.paid){
              this.$router.push({path:'/'})
            } else {
              browserDb.setLItem('foregroundUser', res.data.attributes.username);
              if (this.siteMode === 'pay'){
                this.$router.push({path:'pay-circle-login'});
              } else if (this.siteMode === 'public'){
                this.$router.push({path:'/'});
              } else {
               //缺少参数，请刷新页面"
              }
            }

          })

          /*if (this.siteMode === 'pay'){
            this.$router.push({path:'pay-circle-login'});
          } else if (this.siteMode === 'public'){
            this.$router.push({path:'/'});
          } else {
            //缺少参数，请刷新页面"
          }*/
        }

      }).catch(err=>{
      })
    },


    /*
    * 接口请求
    * */
    getForum(){
      return this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          browserDb.setLItem('siteInfo', res.readdata);
        }
        return res
      }).catch(err=>{
      })
    },
    getUsers(id){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        headers:{'Authorization': 'Bearer ' + browserDb.getLItem('Authorization')},
        data:{
          include:['groups']
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          return res;
        }
      }).catch(err=>{
      })
    }

  },

  mounted(){
  },

  created(){
    let isWeixin = this.appCommonH.isWeixin().isWeixin;
    let isPhone = this.appCommonH.isWeixin().isPhone;

   this.getForum().then(res=>{
     if (isWeixin === true) {
       //微信登录
       if (!res.readdata._data.passport.offiaccount_close) {
         this.wxLoginShow = false;
       }
     } else if (isPhone === true) {
       //手机浏览器登录
       this.wxLoginShow = false;
       this.isOne = true;
     } else {
       //pc登录'
       if (!res.readdata._data.passport.oplatform_close) {
         this.wxLoginShow = false;
       }
       this.isPC = true;
     }
   });

  }

}
