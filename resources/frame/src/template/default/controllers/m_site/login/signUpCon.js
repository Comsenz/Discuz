
import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import  '../../../scss/m_site/mobileIndex.scss';
import browserDb from "../../../../../helpers/webDbHelper";
import appCommonH from "../../../../../helpers/commonHelper";

export default {
  data:function () {
    return {
      username:'',
      password:'',
      btnLoading:false,     //注册按钮状态
      error:false,          //错误状态
      errorMessage:"",      //错误信息

      phoneStatus:'',       //绑定手机号状态
      siteMode:'',          //站点是否付费
    }
  },

  components:{
    SignUpHeader,
    SignUpFooter
  },
  methods:{
    signUpClick(){

      this.appFetch({
        url:'register',
        method:'post',
        data:{
          "data": {
          "type": "users",
          "attributes": {
              username:this.username,
              password:this.password
          },
          }
        }
      }).then(res => {
        console.log(res);
        this.getForum().then(()=>{
          if (res.errors){
            let errorInfo = this.appCommonH.errorHandling(res.errors,true);
            this.$toast.fail(errorInfo[0].errorDetail);
          } else {
            this.$toast.success('注册成功');
            let token = res.data.attributes.access_token;
            let tokenId = res.data.id;

            browserDb.setLItem('Authorization', token);
            browserDb.setLItem('tokenId', tokenId);
            console.log(this.phoneStatus);

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
        })
      }).catch(err=>{
        console.log(err);
      })

    },
    //错误提示
    clearError(str){
      switch (str){
        case 'clear' :
          this.error = false;
          this.errorMessage = "";
          break;
        case 'blur':
          if (this.password !== ''){
            this.error = true;
          }
          break;
        default:
          this.error = false;
      };
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
        if (res.errors){
          let errorInfo = this.appCommonH.errorHandling(res.errors,true);
          this.$toast.fail(errorInfo[0].errorDetail);
        } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.setsite.site_mode;
          browserDb.setLItem('siteInfo',res.readdata);
        }
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getForum();
  }

}
