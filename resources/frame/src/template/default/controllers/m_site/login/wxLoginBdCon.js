/*
* 微信登录绑定控制器
* */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDB from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      userName:'',    //用户名
      password:'',    //密码
      siteMode:'',    //站点信息
      openid:'',       //微信openid
      wxurl:''
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
              openid:this.openid
            },
          }
        }
      }).then(res => {
        console.log(res);

        if (res.errors){
          let errorInfo = this.appCommonH.errorHandling(res.errors,true);
          this.$toast.fail(errorInfo[0].errorDetail);
        } else {
          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          webDB.setLItem('Authorization', token);
          webDB.setLItem('tokenId', tokenId);

          this.$router.push({path:webDB.getSItem('beforeVisiting')});

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
          sessionId:sessionId
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          console.log(res.errors[0].status);
          console.log(res.errors[0].user.openid);

          let wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (wxStatus == 400){
            console.log('微信跳转');
            this.openid = openid;
            webDB.setLItem('openid',openid);
            this.$router.push({path: '/wx-login-bd'});
          }
        } else if (res.data.attributes.location) {
          console.log(res.data.attributes.loscation);
          console.log('获取地址');
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location
        } else if (res.data.attributes.access_token){

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          webDB.setLItem('Authorization', token);
          webDB.setLItem('tokenId', tokenId);
          this.$router.push({path:'/'});

        } else {
          console.log('任何情况都不符合');
          console.log(res.data.attributes.location);
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res);
        this.siteMode = res.readdata._data.setsite.site_mode;
        webDB.setLItem('siteInfo',res.readdata);
      }).catch(err=>{
        console.log(err);
      })
    },
    getUsers(id){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        headers:{'Authorization': 'Bearer ' + webDB.getLItem('Authorization')},
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
  created(){
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;

    console.log(code);
    console.log(state);

    webDB.setLItem('code',code);
    webDB.setLItem('state',state);

    if (!code && !state){
      this.getWatchHref()
    } else {
      this.getWatchHref(code,state,sessionId);
    }

    this.getForum();
  }
}
