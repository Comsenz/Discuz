
import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      phoneStatus:"",
      siteMode:''
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  methods:{
    signUpBdClick(){
      this.appFetch({
        url:'register',
        method:'post',
        data:{
          "data": {
          "type": "users",
          "attributes": {
              username:this.userName,
              password:this.password
          },
          }
        }
      }).then(res => {
        console.log(res);

        if (res.errors){
          this.$toast.fail(res.errors[0].code)
        } else {
        this.$toast.success('注册成功');
        // let token = res.data.attributes.access_token;

        if (this.phoneStatus){
          this.$router.push({path:'bind-phone'});
        } else if (this.siteMode === 'pay'){
          this.$router.push({path:'pay-the-fee'});
        } else if (this.siteMode === 'public'){
          this.$router.push({path:'/'});
        } else {
          console.log("缺少参数，请刷新页面");
        }

        }

      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.siteMode =  webDb.getLItem('siteInfo')._data.setsite.site_mode;
    this.phoneStatus = webDb.getLItem('siteInfo')._data.qcloud.qcloud_sms;
  }

}
