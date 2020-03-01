
/*
* 确认支付密码控制器
* */

import confirmPayPwdHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import webDb from '../../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      value: '',
      confirmValue:'',
      showKeyboard: true,
      errorInfo:'',
      userId:''
    }
  },
  methods:{
    onInput(key) {
      this.value = (this.value + key).slice(0, 6);
    },
    onDelete() {
      this.value = this.value.slice(0, this.value.length - 1);
    },
    submitClick(){
      if (this.value.length < 6) {
        this.errorInfo = '密码错误';
      } else if (this.value !== this.confirmValue){
        this.errorInfo = '请保持支付密码一致'
      } else {
        this.errorInfo = '';
        this.editUser(this.userId);
      }
    },

    //接口请求
    editUser(id){
      this.appFetch({
        url:"users",
        method:'patch',
        splice:'/' + id,
        data:{
          data:{
            "attributes": {
              "payPassword": this.value,
              "pay_password_confirmation": this.confirmValue,
              'pay_password_token':webDb.getLItem('payPwdToken')?webDb.getLItem('payPwdToken'):''
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$toast.success('设置成功');
          if (webDb.getLItem('payUrl')){
            this.$router.push({path:webDb.getLItem('payUrl')})
          } else {
            this.$router.push({path:"modify-data"});
          }
          webDb.setLItem('payUrl','');
          webDb.setLItem('payPwdToken','');
        }
      }).catch(err=>{
      })
    }
  },
  created(){
    this.confirmValue = this.$route.params.value;
    this.userId = webDb.getLItem('tokenId');
  },
  components:{
    confirmPayPwdHeader
  }
}
