/*
* 验证身份管理器
* */

import verifyPayPwdHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import webDb from '../../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      value: '',
      showKeyboard: true,
      pwdShow:true,
      modifyPhone:'',        //是否绑定手机号
      loading:false,         //验证状态
    }
  },
  methods:{
    onInput(key) {
      this.value = (this.value + key).slice(0, 6);
      if (this.value.length === 6){
        this.setPwd();
      }
    },
    onDelete() {
      this.value = this.value.slice(0, this.value.length - 1);
    },


    /*
    * 接口请求
    * */
    setPwd(){
      this.loading = true;
      this.appFetch({
        url:"verifyPayPwd",
        method:"post",
        data:{
          "pay_password": this.value
        }
      }).then(res=>{
        // console.log(res);
        this.loading = false;
        if (res.errors){
          this.value = '';
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          webDb.setLItem('payPwdToken',res.token);
          this.$router.replace({path:"/setup-pay-pwd"})
        }
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.pwdShow = webDb.getLItem('siteInfo')._data.qcloud.qcloud_sms;
    this.modifyPhone = this.$route.query.modifyPhone;
  },
  components: {
    verifyPayPwdHeader
  },
}
