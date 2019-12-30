
import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      phoneStatus:"",
      siteMode:'',
      openid:''
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
              password:this.password,
              openid:this.openid
          },
          }
        }
      }).then(res => {
        console.log(res);

        if (res.errors){
          this.$toast.fail(res.errors[0].code)
        } else {
        this.$toast.success('注册成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;

          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);

        this.getForum().then(()=>{
          if (this.phoneStatus){
            this.$router.push({path:'bind-phone'});
          } else if (this.siteMode === 'pay'){
            this.$router.push({path:'pay-the-fee'});
          } else if (this.siteMode === 'public'){
            this.$router.push({path:'/'});
          } else {
            console.log("缺少参数，请刷新页面");
          }
        })

        }

      }).catch(err=>{
        console.log(err);
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
        console.log(res);
        this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
        this.siteMode = res.readdata._data.setsite.site_mode;
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getForum();
    this.openid = webDb.getLItem('openid');
  }

}
