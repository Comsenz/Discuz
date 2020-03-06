/*
* 微信登录绑定控制器
* */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';
import appCommonH from "../../../../../helpers/commonHelper";

export default {
  data:function () {
    return {
      userName:'',    //用户名
      password:'',    //密码
      siteMode:'',    //站点信息
      openid:'',       //微信openid
      wxurl:'',
      platform:''
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  methods:{
    loginBdClick(){
      this.appFetch({
        url: "login",
        method: "post",
        data: {
          "data": {
            "attributes": {
              username: this.userName,
              password: this.password,
              openid:this.openid,
              platform:this.platform
            },
          }
        }
      }).then(res => {

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
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);

          this.$router.push({path:webDb.getSItem('beforeVisiting')});

          this.getUsers(tokenId).then(res=>{
            if (res.readdata._data.paid){
              this.$router.push({path:'/'})
            } else {
              webDb.setLItem('foregroundUser', res.data.attributes.username);
              if (this.siteMode === 'pay'){
                this.$router.push({path:'pay-circle-login'});
              } else if (this.siteMode === 'public'){
                this.$router.push({path:'/'});
              } else {
                //缺少参数，请刷新页面
              }
            }

          })

        }
      }).catch(err => {
      })

    },

    /*
    * 接口请求
    * */
    getWatchHref(code,state,sessionId){
      this.appFetch({
        url:'wechat',
        method:'get',
        data:{
          code:code,
          state:state,
          sessionId:sessionId,
        }
      }).then(res=>{
        if (res.errors){

          let wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (wxStatus == 400){
            //微信跳转
            this.openid = openid;
            webDb.setLItem('openid',openid);
            this.$router.push({path: '/wx-login-bd'});
          }
        } else if (res.data.attributes.location) {
          //获取地址
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location
        } else if (res.data.attributes.access_token){

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          let beforeVisiting = webDb.getSItem('beforeVisiting');

          if (beforeVisiting) {
            this.$router.replace({path: beforeVisiting});
            webDb.setSItem('beforeState',1);
          } else {
            this.$router.push({path: '/'});
          }

        } else {
          //任何情况都不符合
        }
      }).catch(err=>{
      })
    },
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        this.siteMode = res.readdata._data.set_site.site_mode;
        webDb.setLItem('siteInfo',res.readdata);
      }).catch(err=>{
      })
    },
    getUsers(id){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        headers:{'Authorization': 'Bearer ' + webDb.getLItem('Authorization')},
        data:{
          include:['groups']
        }
      }).then(res=>{
        if(res.errors){
            this.$toast.fail(res.errors[0].code);
        }else {
          return res;
        }
      }).catch(err=>{
      })
    },
    getWatchHrefPC(code,state,sessionId){
      this.appFetch({
        url:'wxLogin',
        method:'get',
        data:{
          code:code,
          state:state,
          sessionId:sessionId,
        }
      }).then(res=>{
        if (res.errors){

          let wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (wxStatus == 400){
            //微信跳转
            this.openid = openid;
            webDb.setLItem('openid',openid);
            this.$router.push({path: '/wx-login-bd'});
          }
        } else if (res.data.attributes.location) {
          //获取地址
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location;
        } else if (res.data.attributes.access_token){

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          let beforeVisiting = webDb.getSItem('beforeVisiting');

          if (beforeVisiting) {
            this.$router.replace({path: beforeVisiting});
            webDb.setSItem('beforeState',1);
          } else {
            this.$router.push({path: '/'});
          }

        } else {
          //任何情况都不符合
        }
      }).catch(err=>{
      })
    }

  },
  created(){
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;
    let isWeixin = appCommonH.isWeixin().isWeixin;
    this.openid = webDb.getLItem('openid');
    console.log('进入登录页');


    // webDb.setLItem('code',code);
    // webDb.setLItem('state',state);

    if (isWeixin){
      this.platform = 'mp';
      if (!code && !state){
        // this.getWatchHref()
      } else {
        // this.getWatchHref(code,state,sessionId);
      }
    }else {
      this.platform = 'dev';
      if (this.openid === ''){
        //PC端：没有openid
        // this.getWatchHrefPC(code,state,sessionId);
      }
    }

    this.getForum();
  }
}
