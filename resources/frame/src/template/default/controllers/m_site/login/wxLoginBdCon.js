
import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDB from '../../../../../helpers/webDbHelper';
import {mapState} from 'vuex';

export default {
  data:function () {
    return {
      userName:'',    //用户名
      password:'',    //密码
      siteMode:'',    //站点信息
      openid:''       //微信openid
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },
  computed:mapState({
    openidX:state => state.appSiteModule.openid
  }),

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
              openid:this.openidX
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
          webDB.setLItem('Authorization', token);
          webDB.setLItem('tokenId', tokenId);

          if(this.siteMode === 'pay'){
            this.$router.push({path:'pay-circle-login'})
          } else if (this.siteMode === 'public'){
            this.$router.push({path:'/'})
          } else {
            console.log("缺少参数，请刷新页面");
          }

          /*if (this.phoneStatus){
            this.$router.push({path:'bind-phone'});
          } else if (this.siteMode === 'pay'){
            this.$router.push({path:'pay-the-fee'});
          } else if (this.siteMode === 'public'){
            this.$router.push({path:'/'});
          } else {
            console.log("缺少参数，请刷新页面");
          }*/
        }
      }).catch(err => {
        console.log(err);
      })

    }
  },
  created(){
    console.log(this.openidX);
    this.siteMode = webDB.getLItem('siteInfo')._data.setsite.site_mode;
  }
}
