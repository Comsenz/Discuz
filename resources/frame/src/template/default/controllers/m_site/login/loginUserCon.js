/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter';
import browserDb from '../../../../../helpers/webDbHelper';

import {mapMutations,mapState} from 'vuex';

export default {
  data: function () {
    return {
      userName: "",
      password: "",
      userId: '2',
      btnLoading: false,
      wxLoginShow: true,
      isOne: false,
      siteMode:'',           //站点模式
      phoneStatus:'',        //是否开启手机绑定
      wxHref:'',             //微信获取openid链接
      isPC:false,            //是否PC端
      isCodeState:0,         //第一次不带参数，第二次带参数。超过两次再请求不带参数
      wxStatus:"",           //微信登录
    }
  },
  /*
  * 映射appSiteModule/state内定义的属性
  * */
  computed:mapState({
    status:state => state.appSiteModule.status,
  }),

  mounted(){

  },

  methods: {
    /*
    * 映射mutation内方法
    * */
    ...mapMutations({
      setStatus:'appSiteModule/SET_STATUS',
      setOpenId:'appSiteModule/SET_OPENID'
    }),

    loginClick() {
      this.setStatus('啊啦啦啦');
      this.btnLoading = true;

      this.appFetch({
        url: "login",
        method: "post",
        data: {
          "data": {
            "attributes": {
              username: this.userName,
              password: this.password,
            },
          }
        }
      }).then(res => {
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
            if (res.errors){
              let errorInfo = this.appCommonH.errorHandling(res.errors,true);
              this.$toast.fail(errorInfo[0].errorDetail);
            } else {
              if (res.readdata._data.paid) {
                browserDb.setLItem('foregroundUser', res.data.attributes.username);
                let beforeVisiting = browserDb.getSItem('beforeVisiting');

                if (beforeVisiting) {
                  this.$router.push({path: beforeVisiting})
                } else {
                  this.$router.push({path: '/'})
                }

              } else {
                if (this.siteMode === 'pay') {
                  this.$router.push({path: 'pay-circle-login'});
                } else if (this.siteMode === 'public') {
                  this.$router.push({path: '/'});
                } else {
                  //缺少参数，请刷新页面
                }
              }
            }
          })
        }

      }).catch(err => {
      })

    },

    loginWxClick() {
     this.getWxLogin().then(res=>{
       if (res.errors){
         this.$toast.fail(res.errors[0].code);
       }else {
         window.location.href = res.readdata._data.location
       }
     })
    },

    loginPhoneClick() {
      this.$router.push({path: '/login-phone'})
    },

    /*
    * 接口请求
    * */
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        // if (res.errors){
        //   this.$toast.fail(res.errors[0].code);
        // } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          browserDb.setLItem('siteInfo', res.readdata);
        // }
      }).catch(err=>{
      })
    },
    /*getWatchHref(code,state){
      this.appFetch({
        url:'wechat',
        method:'get',
        data:{
          code:code,
          state:state
        }
      }).then(res=>{
        if (res.errors){
          this.wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (this.wxStatus == 400){
            //微信跳转
            this.setOpenId(openid);
            this.$router.push({path: '/wx-login-bd'})
          }
        } else {
          this.$router.push({path:'/'})
        }
        // this.isCodeState = false;
        this.wxHref = res.data.attributes.location;
      }).catch(err=>{
      })
    },*/
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
    },
    getWxLogin(){
      return this.appFetch({
        url:'wxLogin',
        method:"get",
        data:{}
      }).then(res=>{
        return res
      }).catch(err=>{
      })
    }
  },
  created() {
    // localStorage.clear();

    let isWeixin = this.appCommonH.isWeixin().isWeixin;
    let isPhone = this.appCommonH.isWeixin().isPhone;


    //获取到code 和 state再访问getWatchHref接口，把code和state拼接到接口url里。

    if (isWeixin === true) {
      //微信登录
    } else if (isPhone === true) {
      //手机浏览器登录
      this.wxLoginShow = false;
      this.isOne = true;
    } else {
      //pc登录
      this.isPC = true;
    }

    this.getForum();

  },
  components: {
    LoginHeader,
    LoginFooter
  },
}
