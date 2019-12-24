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

      console.log(this.status);

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
        console.log(res);

        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          browserDb.setLItem('Authorization', token);
          browserDb.setLItem('tokenId', tokenId);

          this.getUsers(tokenId).then(res=>{
            if (res.readdata._data.paid){
              this.$router.push({path:'/'})
            } else {
              if (this.siteMode === 'pay'){
                this.$router.push({path:'pay-circle-login'});
              } else if (this.siteMode === 'public'){
                this.$router.push({path:'/'});
              } else {
                console.log("缺少参数，请刷新页面");
              }
            }

          })

        }

      }).catch(err => {
        console.log(err);
      })

    },

    loginWxClick() {
      if (this.isPC){
        this.$message({
          message: 'PC端暂不支持微信登录，请在微信客户端打开',
          type: 'warning'
        });
      }else {

      }

      // window.location.href = this.wxHref;
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
        console.log(res);
        this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
        this.siteMode = res.readdata._data.setsite.site_mode;
        browserDb.setLItem('siteInfo',res.readdata);
      }).catch(err=>{
        console.log(err);
      })
    },
    getWatchHref(code,state){
      this.appFetch({
        url:'wechat',
        method:'get',
        data:{
          code:code,
          state:state
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          console.log(res.errors[0].status);
          this.wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (this.wxStatus == 400){
            console.log('微信跳转');
            this.setOpenId(openid);
            this.$router.push({path: '/wx-login-bd'})
          }
        } else {
          this.$router.push({path:'/'})
        }
        // this.isCodeState = false;
        this.wxHref = res.data.attributes.location;
      }).catch(err=>{
        console.log(err);
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
        console.log(res);
        return res;
        //paid
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created() {
    let isWeixin = this.appCommonH.isWeixin().isWeixin;
    let isPhone = this.appCommonH.isWeixin().isPhone;

    console.log(this.$router.history);
    console.log(this.$router.history.current.query.code);
    console.log(this.$router.history.current.query.state);

    //获取到code 和 state再访问getWatchHref接口，把code和state拼接到接口url里。

    if (isWeixin === true) {
      // const APPID = 'wx2aa96b3508831102';
      // const REDIRECT_URI = window.location.host;
      // const SCOPE = 'snsapi_base';
      // const STATE = '123';
      //微信登录时
      console.log('微信登录');
      // this.getWatchHref();

      // this.getWatchHref(this.$router.history.current.query.code,this.$router.history.current.query.state);
      // if (this.$router.history.current.query.code && this.$router.history.current.query.state){
      //   this.$router.push({path:'/login-user'});
      // }
    } else if (isPhone === true) {
      console.log('手机浏览器登录');

      this.wxLoginShow = false;
      this.isOne = true;
    } else {
      console.log('pc登录');

      this.isPC = true;
      // this.getWatchHref();
    }

    this.getForum();

  },
  components: {
    LoginHeader,
    LoginFooter
  },
}
