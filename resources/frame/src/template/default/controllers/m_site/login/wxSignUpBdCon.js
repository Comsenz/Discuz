
import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';
import appCommonH from "../../../../../helpers/commonHelper";

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      phoneStatus:"",
      siteMode:'',
      openid:'',
      platform:'',
      signReason:'',        //注册原因
      signReasonStatus:false,
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  methods:{
    signUpBdClick(){

      if (this.signReasonStatus) {
        if (this.signReason.length < 1){
          this.$toast.fail('请填写注册原因！');
        } else {
          this.setSignData();
        }
      } else {
        this.setSignData();
      }


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
          this.signReasonStatus = res.readdata._data.set_reg.register_validate;
        }

      }).catch(err=>{
        console.log(err);
      })
    },
    setSignData(){
      this.appFetch({
        url:'register',
        method:'post',
        data:{
          "data": {
            "type": "users",
            "attributes": {
              username:this.userName,
              password:this.password,
              openid:this.openid,
              platform:this.platform,
              register_reason:this.signReason
            },
          }
        }
      }).then(res => {
        if (res.errors){
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            if (res.rawData[0].code === 'register_validate'){
              this.$router.push({path:"information-page",query:{setInfo:'registrationReview'}})
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          }
        } else {
          this.$toast.success('注册成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;

          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken',refreshToken);

          this.getForum().then(()=>{
            if (this.phoneStatus){
              this.$router.push({path:'bind-phone'});
            } else if (this.siteMode === 'pay'){
              this.$router.push({path:'pay-the-fee'});
            } else if (this.siteMode === 'public'){
              this.$router.push({path:'/'});
            } else {
              //缺少参数，请刷新页面
            }
          })

        }

      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getForum();
    this.openid = webDb.getLItem('openid');
    let isWeixin = appCommonH.isWeixin().isWeixin;

    if(isWeixin){
      this.platform = 'mp';
    }else {
      this.platform = 'dev';
    }

  }

}
